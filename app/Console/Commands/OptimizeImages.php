<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
                            {--path= : Subdirectory within storage/app/public to scan (default: media)}
                            {--quality=78 : WebP output quality (1–100)}
                            {--force : Re-generate even if WebP already exists}
                            {--dry-run : Show what would be generated without writing files}';

    protected $description = 'Convert storage media images to WebP and generate responsive size derivatives.';

    /** Target widths in pixels. Images narrower than a target are NOT upscaled. */
    private const TARGET_WIDTHS = [400, 800, 1200];

    /** Supported input extensions (case-insensitive). */
    private const INPUT_EXTENSIONS = ['jpg', 'jpeg', 'png', 'jfif'];

    public function handle(): int
    {
        // ── Pre-flight checks ─────────────────────────────────────────────────
        if (! extension_loaded('gd')) {
            $this->error('GD extension is not loaded. Install php-gd and re-run.');
            return self::FAILURE;
        }

        $gdInfo = gd_info();
        if (empty($gdInfo['WebP Support'])) {
            $this->error('GD is loaded but WebP support is missing. Recompile PHP with WebP or install libwebp.');
            return self::FAILURE;
        }

        $quality  = (int) $this->option('quality');
        $force    = (bool) $this->option('force');
        $dryRun   = (bool) $this->option('dry-run');
        $subPath  = $this->option('path') ?? 'media';

        if ($quality < 1 || $quality > 100) {
            $this->error("--quality must be between 1 and 100 (got {$quality}).");
            return self::FAILURE;
        }

        $basePath = storage_path('app/public/' . ltrim($subPath, '/'));

        if (! is_dir($basePath)) {
            $this->error("Directory does not exist: {$basePath}");
            return self::FAILURE;
        }

        if ($dryRun) {
            $this->warn('[DRY-RUN] No files will be written.');
        }

        $this->info("Scanning: {$basePath}");
        $this->info("Quality: {$quality}  Force: " . ($force ? 'yes' : 'no'));
        $this->newLine();

        // ── Collect all eligible files ────────────────────────────────────────
        $files = $this->collectImageFiles($basePath);

        if (empty($files)) {
            $this->info('No eligible image files found.');
            return self::SUCCESS;
        }

        $this->info('Found ' . count($files) . ' source image(s).');
        $this->newLine();

        $generated = 0;
        $skipped   = 0;
        $failed    = 0;
        $savedBytes = 0;

        foreach ($files as $srcPath) {
            $relPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $srcPath);
            $this->line("<fg=cyan>Processing:</> {$relPath}");

            [$src, $origW, $origH] = $this->loadImage($srcPath);

            if ($src === null) {
                $this->warn("  ✗ Could not load image — skipping.");
                $failed++;
                continue;
            }

            $dir      = dirname($srcPath);
            $basename = pathinfo($srcPath, PATHINFO_FILENAME);

            foreach (self::TARGET_WIDTHS as $targetW) {
                // Do not upscale
                if ($targetW > $origW) {
                    $this->line("  · Skipping {$targetW}w (source width {$origW}px < target).");
                    continue;
                }

                $targetH   = (int) round($origH * ($targetW / $origW));
                $outputPath = "{$dir}/{$basename}-{$targetW}.webp";

                if (! $force && file_exists($outputPath)) {
                    $this->line("  · <fg=yellow>Exists</> {$basename}-{$targetW}.webp — skip (use --force to regenerate).");
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $this->line("  · [DRY-RUN] Would generate: {$basename}-{$targetW}.webp ({$targetW}×{$targetH}).");
                    continue;
                }

                $dst = $this->resample($src, $origW, $origH, $targetW, $targetH);
                if ($dst === null) {
                    $this->warn("  ✗ Resample failed for {$targetW}w.");
                    $failed++;
                    continue;
                }

                $ok = imagewebp($dst, $outputPath, $quality);
                imagedestroy($dst);

                if (! $ok || ! file_exists($outputPath)) {
                    $this->warn("  ✗ Failed to write: {$outputPath}");
                    $failed++;
                    continue;
                }

                $origFileSize = filesize($srcPath);
                $webpSize     = filesize($outputPath);
                $saving       = max(0, $origFileSize - $webpSize);
                $savePct      = $origFileSize > 0 ? round(($saving / $origFileSize) * 100) : 0;
                $savedBytes  += $saving;

                $this->line("  ✓ <fg=green>Generated</> {$basename}-{$targetW}.webp"
                    . " ({$targetW}×{$targetH}, " . round($webpSize / 1024, 1) . " KB,"
                    . " −{$savePct}% vs source)");

                $generated++;
            }

            imagedestroy($src);
            $this->newLine();
        }

        // ── Summary ───────────────────────────────────────────────────────────
        $this->newLine();
        $this->info('═══════════════════════════════════════════');
        $this->info("Generated: {$generated}   Skipped: {$skipped}   Failed: {$failed}");
        $this->info('Estimated space saved: ' . round($savedBytes / 1024, 1) . ' KB');
        $this->info('═══════════════════════════════════════════');

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Recursively collect all supported image files.
     *
     * @return string[]
     */
    private function collectImageFiles(string $dir): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $ext = strtolower($file->getExtension());
            if (! in_array($ext, self::INPUT_EXTENSIONS, true)) {
                continue;
            }

            // Skip already-generated derivatives (e.g. original-400.webp sibling)
            // We only process the originals (files whose names do NOT end with -NNN)
            if (preg_match('/-\d+$/', $file->getBasename('.' . $ext))) {
                continue;
            }

            $files[] = $file->getRealPath();
        }

        sort($files);
        return $files;
    }

    /**
     * Load an image from disk returning [GdImage, width, height] or [null, 0, 0].
     *
     * @return array{0: \GdImage|null, 1: int, 2: int}
     */
    private function loadImage(string $path): array
    {
        try {
            $mime = mime_content_type($path);
            $src  = match (true) {
                in_array($mime, ['image/jpeg', 'image/jpg', 'image/jfif'], true) => imagecreatefromjpeg($path),
                $mime === 'image/png'  => imagecreatefrompng($path),
                $mime === 'image/webp' => imagecreatefromwebp($path),
                default                => null,
            };

            if ($src === false || $src === null) {
                return [null, 0, 0];
            }

            return [$src, imagesx($src), imagesy($src)];
        } catch (\Throwable $e) {
            return [null, 0, 0];
        }
    }

    /**
     * Create a resized GdImage preserving aspect ratio.
     *
     * @return \GdImage|null
     */
    private function resample(\GdImage $src, int $srcW, int $srcH, int $dstW, int $dstH): ?\GdImage
    {
        $dst = imagecreatetruecolor($dstW, $dstH);
        if ($dst === false) {
            return null;
        }

        // Preserve transparency / alpha channel
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        $ok = imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        if (! $ok) {
            imagedestroy($dst);
            return null;
        }

        return $dst;
    }
}
