<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportSeoArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:import-articles {--folder= : Specific blog folder to import e.g. 002-kot-bengalski-a-dzieci} {--force-now : Force published_at to now for immediate live viewing (requires --folder)} {--force-sync : Overwrite existing CMS posts with disk markdown files}';

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
        $forceNow = (bool) $this->option('force-now');
        $forceSync = (bool) $this->option('force-sync');

        if ($forceNow && ! $targetFolder) {
            $this->warn('Ostrzeżenie: Opcja --force-now została wywołana bez wskazania folderu (--folder). Aby zapobiec nadpisaniu harmonogramu bazy wiedzy, data zostanie przypisana zgodnie z kalendarzem publikacji.');
            $forceNow = false;
        }

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

            // Use explicit publication date from metadata if defined, otherwise calculate Sunday schedule
            if (! empty($metaData['publication_date'])) {
                $time = $metaData['publication_time'] ?? '10:00';
                $scheduledDate = Carbon::parse("{$metaData['publication_date']} {$time}:00");
            } elseif (! empty($metaData['publish_date'])) {
                $scheduledDate = Carbon::parse($metaData['publish_date']);
            }

            // Copy images to public disk + public/storage/blog mirror
            $blogRelativeDir = "blog/{$folderName}";
            $storagePublicDir = public_path("storage/{$blogRelativeDir}");
            File::ensureDirectoryExists($storagePublicDir);
            Storage::disk('public')->makeDirectory($blogRelativeDir);

            $imageFiles = File::files($dirPath);
            foreach ($imageFiles as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'jfif'], true)) {
                    File::copy($file->getPathname(), $storagePublicDir . '/' . $file->getFilename());
                    Storage::disk('public')->put(
                        "{$blogRelativeDir}/" . $file->getFilename(),
                        File::get($file->getPathname())
                    );
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
            $excerpt = $metaData['meta_description']
                ?? ($metaData['meta']['description'] ?? ($metaData['excerpt'] ?? null));

            if (! $excerpt) {
                // Clean markdown headings, images and formatting before taking excerpt
                $cleanText = preg_replace('/^#+\s+.*$/m', '', $processedBody);
                $cleanText = preg_replace('/!\[.*?\]\(.*?\)/s', '', $cleanText);
                $cleanText = preg_replace('/\[(.*?)\]\(.*?\)/s', '$1', $cleanText);
                $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($cleanText))), 160);
            }

            $publishedAt = $forceNow ? now() : $scheduledDate;

            // Check if Post already exists in database (including soft-deleted records to avoid UNIQUE(slug) collision)
            $existingPost = Post::withTrashed()->where('slug', $slug)->first();

            if ($existingPost && ! $forceSync) {
                if ($existingPost->trashed()) {
                    $this->info("Skipped trashed Post ID {$existingPost->id}: {$title} [Wpis w koszu CMS — użyj --force-sync, aby przywrócić]");
                    continue;
                }

                // Ensure published_at has its scheduled Sunday date if missing
                if (! $existingPost->published_at) {
                    $existingPost->update([
                        'published_at' => $scheduledDate,
                        'is_published' => true,
                    ]);
                }

                $this->info("Skipped Post ID {$existingPost->id}: {$title} [Już istnieje w CMS — zachowano edycje]");
                continue;
            }

            $postAttributes = [
                'user_id' => $user->id,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'body' => $processedBody,
                'sections' => Post::parseBodyToSections($processedBody),
                'meta_title' => $metaData['meta_title'] ?? ($metaData['meta']['title'] ?? null),
                'meta_description' => $metaData['meta_description'] ?? ($metaData['meta']['description'] ?? $excerpt),
                'is_published' => true,
                'published_at' => $publishedAt,
            ];

            if ($existingPost) {
                if ($existingPost->trashed()) {
                    $existingPost->restore();
                }
                $existingPost->update($postAttributes);
                $post = $existingPost;
            } else {
                $post = Post::create($postAttributes);
            }

            // Attach Category
            $categoryNames = $metaData['categories'] ?? ['Odmiany i Rasy'];
            if (! is_array($categoryNames)) {
                $categoryNames = [$categoryNames];
            }
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

            // Attach Hero Media (Hero image 02_hero_v2.jpg, 02_hero.jpg, or first image in package)
            $heroFilename = File::exists($storagePublicDir . '/02_hero_v2.jpg') ? '02_hero_v2.jpg' : '02_hero.jpg';
            if (! File::exists($storagePublicDir . '/' . $heroFilename)) {
                $anyImage = collect(File::files($storagePublicDir))
                    ->first(fn ($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'jfif'], true));
                if ($anyImage) {
                    $heroFilename = $anyImage->getFilename();
                }
            }

            $mediaService = app(MediaService::class);
            $post->media()->get()->each(function (Media $oldMedia) use ($mediaService, $blogRelativeDir) {
                if ($oldMedia->directory !== $blogRelativeDir) {
                    $mediaService->delete($oldMedia);
                } else {
                    $oldMedia->delete();
                }
            });

            $heroFullPath = $storagePublicDir . '/' . $heroFilename;
            $heroMime = File::exists($heroFullPath) ? (@mime_content_type($heroFullPath) ?: 'image/jpeg') : 'image/jpeg';
            $heroSize = File::exists($heroFullPath) ? (int) (File::size($heroFullPath) ?: 0) : 0;

            $post->media()->create([
                'disk' => 'public',
                'directory' => $blogRelativeDir,
                'filename' => $heroFilename,
                'mime_type' => $heroMime,
                'size' => $heroSize,
                'title' => $title,
                'alt_text' => $metaData['hero_image']['alt'] ?? $title,
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
    /**
     * Parser for top-level keys, nested maps (e.g. meta.title), and list items in yml metadata.
     */
    private function parseSimpleYaml(string $content): array
    {
        $data = [];
        $lines = preg_split('/\R/', $content) ?: [];
        $currentKey = null;

        $unquote = static function (string $value): string {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return '';
            }
            if ((str_starts_with($trimmed, '"') && str_ends_with($trimmed, '"'))
                || (str_starts_with($trimmed, "'") && str_ends_with($trimmed, "'"))) {
                $trimmed = substr($trimmed, 1, -1);
            }

            return str_replace(['\"', "\\'"], ['"', "'"], $trimmed);
        };

        foreach ($lines as $rawLine) {
            $line = rtrim($rawLine);
            $trimmed = trim($line);

            if ($trimmed === '' || Str::startsWith($trimmed, '#')) {
                continue;
            }

            $indent = strlen($line) - strlen(ltrim($line, " \t"));

            if ($indent === 0 && preg_match('/^([a-z0-9_]+):(.*)$/i', $trimmed, $m)) {
                $key = $m[1];
                $val = $unquote($m[2]);
                if ($val !== '') {
                    $data[$key] = $val;
                    $currentKey = null;
                } else {
                    $currentKey = $key;
                    if (! isset($data[$currentKey]) || ! is_array($data[$currentKey])) {
                        $data[$currentKey] = [];
                    }
                }
            } elseif ($indent > 0 && $currentKey !== null) {
                if (! is_array($data[$currentKey] ?? null)) {
                    $data[$currentKey] = [];
                }

                if (preg_match('/^-\s+([a-z0-9_]+):(.*)$/i', $trimmed, $m)) {
                    $data[$currentKey][] = [$m[1] => $unquote($m[2])];
                } elseif (preg_match('/^-\s*(.*)$/', $trimmed, $m)) {
                    $data[$currentKey][] = $unquote($m[1]);
                } elseif (preg_match('/^([a-z0-9_]+):(.*)$/i', $trimmed, $m)) {
                    $subKey = $m[1];
                    $subVal = $unquote($m[2]);
                    $lastIdx = array_key_last($data[$currentKey]);
                    if ($lastIdx !== null && is_int($lastIdx) && is_array($data[$currentKey][$lastIdx])) {
                        $data[$currentKey][$lastIdx][$subKey] = $subVal;
                    } else {
                        $data[$currentKey][$subKey] = $subVal;
                    }
                }
            }
        }

        return $data;
    }
}
