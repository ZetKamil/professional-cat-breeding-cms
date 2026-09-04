<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportSeoArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:import-articles {--folder= : Specific blog folder to import e.g. 002-kot-bengalski-a-dzieci} {--force-now : Force published_at to now for immediate live viewing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SEO blog article packages from BLOG/ directory into Laravel posts table and media storage with exact Sunday 10:00 schedule.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetFolder = $this->option('folder');
        $forceNow = $this->option('force-now');

        $user = User::first();
        if (! $user) {
            $this->error('No user found in database. Please run UserSeeder first.');
            return Command::FAILURE;
        }

        $blogPath = base_path('BLOG');
        if (! File::exists($blogPath)) {
            $this->error("BLOG directory not found at {$blogPath}");
            return Command::FAILURE;
        }

        $directories = File::directories($blogPath);
        sort($directories);
        $importedCount = 0;

        // Baseline date: Article 001 -> 2026-07-19 (Sunday 10:00), Article 002 -> 2026-07-26 (Sunday 10:00)
        $baseDate = Carbon::parse('2026-07-19 10:00:00');

        foreach ($directories as $index => $dirPath) {
            $folderName = basename($dirPath);

            if (! preg_match('/^\d{3}-/', $folderName)) {
                continue; // Skip non-article folders like "do not read"
            }

            if ($targetFolder && $targetFolder !== $folderName && ! Str::contains($folderName, $targetFolder)) {
                continue;
            }

            $articleMd = $dirPath . '/01_article.md';
            $metadataYml = $dirPath . '/04_content_metadata.yml';

            if (! File::exists($articleMd)) {
                $this->warn("Skipping {$folderName}: 01_article.md missing.");
                continue;
            }

            // Extract numeric ID from folder name e.g. 002 -> index 2
            preg_match('/^(\d{3})/', $folderName, $numMatch);
            $articleNum = isset($numMatch[1]) ? (int) $numMatch[1] : ($index + 1);

            // Calculate Sunday publication date: Article 2 -> 2026-07-26 10:00 (+1 week from 2026-07-19)
            $scheduledDate = $baseDate->copy()->addWeeks($articleNum - 1);

            $this->info("Processing [{$articleNum}] {$folderName} -> Scheduled Date: {$scheduledDate->format('Y-m-d H:i (l)')}");

            // Parse metadata
            $metaData = [];
            if (File::exists($metadataYml)) {
                $metaData = $this->parseSimpleYaml(File::get($metadataYml));
            }

            // Copy images to public storage
            $storagePublicDir = public_path("storage/blog/{$folderName}");
            File::ensureDirectoryExists($storagePublicDir);

            $imageFiles = File::files($dirPath);
            foreach ($imageFiles as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'jfif'], true)) {
                    File::copy($file->getPathname(), $storagePublicDir . '/' . $file->getFilename());
                }
            }

            // Read article body and replace relative image links with public storage URLs
            $rawBody = File::get($articleMd);
            $lines = explode("\n", $rawBody);
            
            // Extract title if first line starts with #
            $title = $metaData['title'] ?? null;
            if (! $title && isset($lines[0])) {
                $title = trim(ltrim($lines[0], '# '));
            }

            // Process image links in markdown
            $processedBody = preg_replace_callback('/!\[([^\]]*)\]\(([^)]+)\)/', function ($matches) use ($folderName) {
                $alt = $matches[1];
                $src = $matches[2];

                if (! Str::startsWith($src, 'http') && ! Str::startsWith($src, '/')) {
                    $src = "/storage/blog/{$folderName}/" . ltrim($src, './');
                }

                return "![{$alt}]({$src})";
            }, $rawBody);

            $slug = $metaData['slug'] ?? Str::slug($title);
            $excerpt = $metaData['meta']['description'] ?? $metaData['excerpt'] ?? Str::limit(strip_tags($processedBody), 160);

            $publishedAt = $forceNow ? now() : $scheduledDate;

            // Create or Update Post
            $post = Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $user->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'body' => $processedBody,
                    'is_published' => true,
                    'published_at' => $publishedAt,
                ]
            );

            // Attach Category
            $categoryNames = $metaData['categories'] ?? ['Odmiany i Rasy'];
            $categoryIds = Category::query()
                ->whereIn('name', $categoryNames)
                ->pluck('id')
                ->all();

            if (empty($categoryIds)) {
                $defaultCategory = Category::first();
                if ($defaultCategory) {
                    $categoryIds = [$defaultCategory->id];
                }
            }

            $post->categories()->sync($categoryIds);

            // Attach Hero Media (Hero image 02_hero.jpg)
            $heroUrl = "/storage/blog/{$folderName}/02_hero.jpg";
            if (! File::exists($storagePublicDir . '/02_hero.jpg')) {
                $anyJpg = collect(File::files($storagePublicDir))->first(fn($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'webp', 'jfif']));
                if ($anyJpg) {
                    $heroUrl = "/storage/blog/{$folderName}/" . $anyJpg->getFilename();
                }
            }

            $post->media()->delete();
            $post->media()->create([
                'disk' => 'public',
                'filename' => $heroUrl,
                'mime_type' => 'image/jpeg',
                'size' => 102400,
                'title' => $title,
                'alt_text' => $title,
                'is_featured' => true,
            ]);

            $statusStr = $publishedAt->isPast() ? "LIVE NOW" : "SCHEDULED FOR {$publishedAt->format('Y-m-d H:i')}";
            $this->info("Imported Post ID {$post->id}: {$title} [{$statusStr}]");
            $importedCount++;
        }

        $this->info("Done! Imported {$importedCount} articles with Sunday 10:00 schedule.");
        return Command::SUCCESS;
    }

    /**
     * Simple parser for key-value and scalar arrays from yml metadata.
     */
    private function parseSimpleYaml(string $content): array
    {
        $data = [];
        $lines = explode("\n", $content);
        $currentKey = null;

        foreach ($lines as $line) {
            $line = rtrim($line);
            if (empty($line) || Str::startsWith(trim($line), '#')) {
                continue;
            }

            if (preg_match('/^([a-z0-9_]+):\s*"?(.*?)"?$/i', trim($line), $m)) {
                $key = $m[1];
                $val = trim($m[2]);
                if ($val !== '') {
                    $data[$key] = $val;
                } else {
                    $currentKey = $key;
                }
            } elseif (preg_match('/^\s+-\s*"?(.*?)"?$/', $line, $m) && $currentKey) {
                $data[$currentKey][] = trim($m[1]);
            }
        }

        return $data;
    }
}
