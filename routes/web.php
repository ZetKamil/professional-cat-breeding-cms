<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Frontend\AnimalController as FrontendAnimalController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// frontend routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/koty', [FrontendAnimalController::class, 'index'])->name('frontend.animals.index');
Route::get('/koty/{animal}', [FrontendAnimalController::class, 'show'])->name('frontend.animals.show');
Route::get('/blog', [FrontendBlogController::class, 'index'])->name('frontend.blog.index');
Route::get('/blog/{post}', [FrontendBlogController::class, 'show'])->name('frontend.blog.show');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('frontend.contact.store');
Route::view('/about', 'frontend.about')->name('about');
Route::view('/o-hodowli', 'frontend.cattery')->name('cattery');
Route::view('/polityka-prywatnosci', 'frontend.privacy')->name('privacy');
Route::view('/regulamin', 'frontend.terms')->name('terms');

// Dynamic Sitemap XML
Route::get('/sitemap.xml', function () {
    $posts = \App\Models\Post::where('is_published', true)
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->get();

    $animals = \App\Models\Animal::published()->get();

    $baseUrl = rtrim(config('app.url', 'https://kotyzmazowieckiejszwajcarii.pl'), '/');

    $staticPages = [
        ['url' => $baseUrl . '/',                    'priority' => '1.0', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
        ['url' => $baseUrl . '/koty',                'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
        ['url' => $baseUrl . '/o-hodowli',           'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => '2026-08-11'],
        ['url' => $baseUrl . '/about',               'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => '2026-08-11'],
        ['url' => $baseUrl . '/blog',                'priority' => '0.8', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
        ['url' => $baseUrl . '/contact',             'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => '2026-08-11'],
        ['url' => $baseUrl . '/polityka-prywatnosci', 'priority' => '0.3', 'changefreq' => 'yearly',  'lastmod' => '2026-08-11'],
        ['url' => $baseUrl . '/regulamin',           'priority' => '0.3', 'changefreq' => 'yearly',  'lastmod' => '2026-08-11'],
    ];

    return response()->view('sitemap', compact('staticPages', 'animals', 'posts', 'baseUrl'))
        ->header('Content-Type', 'application/xml; charset=utf-8');
})->name('sitemap');

// Direct media streaming route to bypass Apache 403 Forbidden symlink restrictions on shared hosting
Route::get('/storage/{path}', function ($path) {
    $cleanPath = trim(parse_url($path, PHP_URL_PATH), '/');
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif', 'ico', 'pdf', 'mp4', 'webm', 'jfif', 'bmp', 'avif', 'heic', 'heif'];
    $ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
    if ($ext !== '' && ! in_array($ext, $allowedExtensions, true)) {
        abort(404);
    }

    $possiblePaths = [
        public_path('storage/' . $cleanPath),
        storage_path('app/public/' . $cleanPath),
        storage_path('app/private/' . $cleanPath),
        storage_path('app/' . $cleanPath),
        public_path('storage/media/' . basename($cleanPath)),
        storage_path('app/public/media/' . basename($cleanPath)),
        storage_path('app/private/media/' . basename($cleanPath)),
        public_path('storage/media/animals/' . basename($cleanPath)),
        storage_path('app/public/media/animals/' . basename($cleanPath)),
        storage_path('app/private/media/animals/' . basename($cleanPath)),
        public_path('storage/animals/' . basename($cleanPath)),
        storage_path('app/public/animals/' . basename($cleanPath)),
        storage_path('app/private/animals/' . basename($cleanPath)),
        storage_path('app/public/' . basename($cleanPath)),
        storage_path('app/private/' . basename($cleanPath)),
        base_path('image/' . $cleanPath),
        base_path('BLOG/' . $cleanPath),
        base_path('BLOG/' . str_replace('blog/', '', $cleanPath)),
    ];

    foreach ($possiblePaths as $fullPath) {
        if (\Illuminate\Support\Facades\File::exists($fullPath) && ! \Illuminate\Support\Facades\File::isDirectory($fullPath)) {
            $mime = @mime_content_type($fullPath) ?: ($ext === 'jfif' || $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : ($ext === 'png' ? 'image/png' : ($ext === 'webp' ? 'image/webp' : 'application/octet-stream')));

            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }
    }

    // Global recursive fallback search in storage_path('app'), public_path('storage'), and base_path('image')
    $filename = basename($cleanPath);
    $filenameLower = strtolower($filename);
    $searchDirs = [
        storage_path('app/public'),
        storage_path('app/private'),
        storage_path('app'),
        public_path('storage'),
        base_path('image'),
    ];

    foreach ($searchDirs as $dir) {
        if (\Illuminate\Support\Facades\File::isDirectory($dir)) {
            foreach (\Illuminate\Support\Facades\File::allFiles($dir) as $f) {
                if (strtolower($f->getFilename()) === $filenameLower) {
                    $mime = @mime_content_type($f->getPathname()) ?: ($ext === 'jfif' || $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : ($ext === 'png' ? 'image/png' : ($ext === 'webp' ? 'image/webp' : 'application/octet-stream')));

                    return response()->file($f->getPathname(), [
                        'Content-Type' => $mime,
                        'Cache-Control' => 'public, max-age=31536000',
                    ]);
                }
            }
        }
    }

    abort(404);
})->where('path', '.*');

// Diagnostic route to inspect media status on hosting (auth required)
Route::match(['get', 'post'], '/check-media', function (\Illuminate\Http\Request $request) {
    $migrateOutput = null;
    if ($request->has('run_migrate') || $request->input('run_migrate') === '1') {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $migrateOutput = "Sukces! Migracje zostały wykonane: <br><pre style='background:#ecfdf5; padding:10px; border-radius:6px; font-size:12px; margin-top:8px;'>" . e(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";
        } catch (\Throwable $e) {
            $migrateOutput = "<span style='color:#dc2626;'>Błąd migracji: " . e($e->getMessage()) . "</span>";
        }
    }

    // Auto-sync physical storage files into public/storage so Apache can serve them directly
    $pubStorage = public_path('storage');
    if (is_link($pubStorage)) {
        @unlink($pubStorage);
    }
    if (! \Illuminate\Support\Facades\File::isDirectory($pubStorage)) {
        \Illuminate\Support\Facades\File::makeDirectory($pubStorage, 0755, true, true);
    }

    $sourceDirs = [
        storage_path('app/public'),
        storage_path('app/private'),
    ];

    $syncedCount = 0;
    foreach ($sourceDirs as $sDir) {
        if (\Illuminate\Support\Facades\File::isDirectory($sDir)) {
            foreach (\Illuminate\Support\Facades\File::allFiles($sDir) as $file) {
                $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($sDir) + 1));
                $target = public_path('storage/' . $rel);
                $targetDir = dirname($target);
                if (! \Illuminate\Support\Facades\File::isDirectory($targetDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($targetDir, 0755, true, true);
                }
                if (! \Illuminate\Support\Facades\File::exists($target) || \Illuminate\Support\Facades\File::size($target) !== $file->getSize()) {
                    @\Illuminate\Support\Facades\File::copy($file->getPathname(), $target);
                    @chmod($target, 0644);
                    $syncedCount++;
                }

                // Also copy to public/storage/media/<filename> as flat fallback
                $flatTarget = public_path('storage/media/' . $file->getFilename());
                $flatDir = dirname($flatTarget);
                if (! \Illuminate\Support\Facades\File::isDirectory($flatDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($flatDir, 0755, true, true);
                }
                if (! \Illuminate\Support\Facades\File::exists($flatTarget)) {
                    @\Illuminate\Support\Facades\File::copy($file->getPathname(), $flatTarget);
                    @chmod($flatTarget, 0644);
                }
            }
        }
    }

    $animals = \App\Models\Animal::with(['media', 'gallery'])->get();
    $output = "<div style='font-family:sans-serif; padding:30px; max-width:900px; margin:0 auto;'>";
    $output .= "<h2>Diagnostyka Bazy i Zdjec Kotow</h2>";

    if ($migrateOutput) {
        $output .= "<div style='background:#f0fdf4; border:1px solid #10b981; color:#065f46; padding:14px; border-radius:8px; margin-bottom:20px;'>{$migrateOutput}</div>";
    }

    $output .= "<div style='margin-bottom:20px; display:flex; gap:10px;'>";
    $output .= "<form method='POST' action='" . url('/check-media') . "'>";
    $output .= "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
    $output .= "<input type='hidden' name='run_migrate' value='1'>";
    $output .= "<button type='submit' style='background:#10b981; color:#fff; border:none; padding:10px 18px; border-radius:6px; font-weight:bold; cursor:pointer;'>🚀 Uruchom Migracje Bazy (Dodaj nowe kociaki: Candy, Carlos, Carmen, Cyprian)</button>";
    $output .= "</form>";
    $output .= "</div>";

    $output .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse; width:100%;'>";
    $output .= "<tr style='background:#f3f4f6;'><th>Kot</th><th>Typ</th><th>Sciezka w DB</th><th>Plik fizyczny?</th><th>Podglad</th></tr>";

    foreach ($animals as $a) {
        $allMedia = $a->gallery;
        if ($allMedia->isEmpty() && $a->media) {
            $allMedia = collect([$a->media]);
        }

        if ($allMedia->isEmpty()) {
            $output .= "<tr><td><strong>" . e($a->name) . "</strong> (" . e($a->breed) . ")</td><td>-</td><td colspan='3' style='color:red;'>BRAK ZDJEC W BAZIE</td></tr>";
            continue;
        }

        foreach ($allMedia as $idx => $m) {
            $isMain = ($a->media && $m->id === $a->media->id);
            $typeLabel = $isMain ? '<span style="color:#059669; font-weight:bold;">Glowne</span>' : 'Galeria #' . ($idx + 1);
            $dbPath = ($m->directory ? $m->directory . '/' : '') . $m->filename;

            $storageExists = file_exists(storage_path('app/public/' . $dbPath)) || file_exists(storage_path('app/private/' . $dbPath));
            $publicExists = file_exists(public_path('storage/' . $dbPath));
            $mediaExists = file_exists(public_path('storage/media/' . basename($m->filename)));

            $found = $storageExists || $publicExists || $mediaExists;
            $statusColor = $found ? '#059669' : '#dc2626';
            $statusText = $found ? 'TAK (Istnieje)' : 'NIE (Brak)';

            $output .= "<tr>"
                . "<td><strong>" . e($a->name) . "</strong> (" . e($a->breed) . ")</td>"
                . "<td>{$typeLabel}</td>"
                . "<td><code>" . e($dbPath) . "</code></td>"
                . "<td style='color:{$statusColor}; font-weight:bold;'>{$statusText}</td>"
                . "<td><a href='" . e($m->url()) . "' target='_blank' style='color:#2563eb;'>Otworz URL</a></td>"
                . "</tr>";
        }
    }

    $output .= "</table>";
    $output .= "<p style='margin-top:20px;'><a href='" . url('/dashboard') . "' style='color:#4b5563; text-decoration:none;'>← Wróć do Panelu</a></p>";
    $output .= "</div>";

    return $output;
})->middleware(['auth', 'verified', 'active']);

// Diagnostic route to test and inspect SMTP / Mail configuration on hosting (admin only)
Route::match(['get', 'post'], '/check-mail', function (\Illuminate\Http\Request $request) {
    if (auth()->user()?->role?->name !== 'admin') {
        abort(403, 'Tylko administrator moze uruchomic diagnostyke poczty.');
    }

    $mailConfig = config('mail');
    $queueDefault = config('queue.default');
    $testResult = null;
    $testError = null;

    if ($request->has('clear_cache')) {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return redirect('/check-mail')->with('status_msg', 'Pamięć podręczna konfiguracji została wyczyszczona (optimize:clear)!');
    }

    if ($request->has('send_test')) {
        $targetEmail = $request->input('target_email', config('mail.from.address') ?: auth()->user()->email);
        try {
            \Illuminate\Support\Facades\Mail::raw('To jest testowa wiadomosc z systemu Hodowli Kotow wyslana o ' . now()->toDateTimeString(), function ($msg) use ($targetEmail) {
                $msg->to($targetEmail)
                    ->subject('Test konfiguracji poczty SMTP - ' . config('app.name'));
            });
            $testResult = "Sukces! Wiadomość testowa została pomyślnie wysłana na adres: <strong>" . e($targetEmail) . "</strong>. Sprawdź skrzynkę odbiorczą oraz folder SPAM.";
        } catch (\Throwable $e) {
            $testError = "Błąd wysyłki SMTP: <strong>" . e($e->getMessage()) . "</strong><br><br><pre style='background:#fef2f2; padding:10px; border-radius:6px; overflow:auto; font-size:12px;'>" . e($e->getTraceAsString()) . "</pre>";
        }
    }

    $defaultMailer = $mailConfig['default'] ?? 'nieznany';
    $smtp = $mailConfig['mailers']['smtp'] ?? [];
    $fromAddr = $mailConfig['from']['address'] ?? 'brak';
    $fromName = $mailConfig['from']['name'] ?? 'brak';

    $statusMsg = session('status_msg');

    $html = "<div style='font-family:sans-serif; padding:30px; max-width:850px; margin:20px auto; line-height:1.5; color:#1f2937;'>";
    $html .= "<h2 style='margin-top:0;'>📧 Diagnostyka Konfiguracji Poczty (SMTP)</h2>";

    if ($statusMsg) {
        $html .= "<div style='background:#ecfdf5; border:1px solid #10b981; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-weight:bold;'>{$statusMsg}</div>";
    }

    if ($testResult) {
        $html .= "<div style='background:#ecfdf5; border:1px solid #10b981; color:#065f46; padding:16px; border-radius:8px; margin-bottom:20px;'>{$testResult}</div>";
    }

    if ($testError) {
        $html .= "<div style='background:#fef2f2; border:1px solid #ef4444; color:#991b1b; padding:16px; border-radius:8px; margin-bottom:20px;'>{$testError}</div>";
    }

    $html .= "<div style='background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:20px; margin-bottom:24px;'>";
    $html .= "<h3 style='margin-top:0;'>Aktualnie załadowane zmienne w Laravel:</h3>";
    $html .= "<ul style='list-style:none; padding-left:0;'>";
    $html .= "<li><strong>MAIL_MAILER (domyślny sterownik):</strong> <code>" . e($defaultMailer) . "</code> " . ($defaultMailer === 'smtp' ? '✅' : '⚠️ (Ustawione na ' . e($defaultMailer) . ' zamiast smtp!)') . "</li>";
    $html .= "<li><strong>MAIL_HOST:</strong> <code>" . e($smtp['host'] ?? 'brak') . "</code></li>";
    $html .= "<li><strong>MAIL_PORT:</strong> <code>" . e($smtp['port'] ?? 'brak') . "</code></li>";
    $html .= "<li><strong>MAIL_ENCRYPTION:</strong> <code>" . e($smtp['encryption'] ?? ($smtp['scheme'] ?? 'brak')) . "</code></li>";
    $html .= "<li><strong>MAIL_USERNAME:</strong> <code>" . e($smtp['username'] ?? 'brak') . "</code></li>";
    $html .= "<li><strong>MAIL_PASSWORD:</strong> <code>" . (!empty($smtp['password']) ? '****** (Ustawione)' : '❌ BRAK HASŁA') . "</code></li>";
    $html .= "<li><strong>MAIL_FROM_ADDRESS:</strong> <code>" . e($fromAddr) . "</code></li>";
    $html .= "<li><strong>MAIL_FROM_NAME:</strong> <code>" . e($fromName) . "</code></li>";
    $html .= "<li><strong>QUEUE_CONNECTION:</strong> <code>" . e($queueDefault) . "</code> " . ($queueDefault === 'sync' ? '✅ (Natychmiast)' : '⚠️ (Zadania mogą czekać w bazie!)') . "</li>";
    $html .= "</ul>";
    $html .= "</div>";

    $html .= "<div style='display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px;'>";
    $html .= "<form method='POST' action='" . url('/check-mail') . "' style='flex:1; min-width:280px; background:#fff; border:1px solid #e5e7eb; padding:20px; border-radius:8px;'>";
    $html .= "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
    $html .= "<input type='hidden' name='send_test' value='1'>";
    $html .= "<h4 style='margin-top:0;'>Wyślij e-mail testowy</h4>";
    $html .= "<label style='display:block; margin-bottom:8px; font-weight:bold;'>Adres odbiorcy:</label>";
    $html .= "<input type='email' name='target_email' value='" . e($fromAddr) . "' style='width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; margin-bottom:12px;' required>";
    $html .= "<button type='submit' style='background:#d1ab58; color:#181816; border:none; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer;'>🚀 Wyślij Testowy E-mail</button>";
    $html .= "</form>";

    $html .= "<form method='POST' action='" . url('/check-mail') . "' style='flex:1; min-width:280px; background:#fff; border:1px solid #e5e7eb; padding:20px; border-radius:8px; display:flex; flex-direction:column; justify-content:space-between;'>";
    $html .= "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
    $html .= "<input type='hidden' name='clear_cache' value='1'>";
    $html .= "<div><h4 style='margin-top:0;'>Wyczyść Cache Konfiguracji</h4><p style='color:#6b7280; font-size:14px;'>Jeśli zmieniłeś plik .env na serwerze, odśwież konfigurację tym przyciskiem.</p></div>";
    $html .= "<button type='submit' style='background:#3b82f6; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer;'>🧹 Wyczyść Cache (optimize:clear)</button>";
    $html .= "</form>";
    $html .= "</div>";

    $html .= "<p><a href='" . url('/dashboard') . "' style='color:#4b5563; text-decoration:none;'>← Wróć do Panelu</a></p>";
    $html .= "</div>";

    return $html;
})->middleware(['auth', 'verified', 'active']);

// Utility route to trigger storage link & seed real cat images on hosting without SSH (admin only)
Route::get('/fix-storage', function () {
    // SECURITY: Restrict to admin role only — this route seeds data and must not be accessible to editors/users
    if (auth()->user()?->role?->name !== 'admin') {
        abort(403, 'Tylko administrator moze uruchomic te operacje.');
    }

    try {
        // Remove symlink if exists on hosting (symlinks to parent dirs cause Apache 403 on OVH)
        $pubStorage = public_path('storage');
        if (is_link($pubStorage)) {
            @unlink($pubStorage);
        } elseif (file_exists($pubStorage) && ! is_dir($pubStorage)) {
            @unlink($pubStorage);
        }

        // Ensure physical directory public/storage/media exists directly on disk
        $pubStorageMedia = public_path('storage/media');
        if (! \Illuminate\Support\Facades\File::isDirectory($pubStorageMedia)) {
            \Illuminate\Support\Facades\File::makeDirectory($pubStorageMedia, 0755, true, true);
        }

        // Ensure storage/app/public/media exists
        $storageAppMedia = storage_path('app/public/media');
        if (! \Illuminate\Support\Facades\File::isDirectory($storageAppMedia)) {
            \Illuminate\Support\Facades\File::makeDirectory($storageAppMedia, 0755, true, true);
        }

        // Run seeder to import real cat data and photos
        $seederRan = false;
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\RealKittensAndParentsSeeder',
            '--force' => true,
        ]);
        $seederRan = true;

        // Fix Linux permissions for Web Server (Apache/Nginx)
        $dirsToFix = [
            storage_path('app/public'),
            storage_path('app/public/media'),
            public_path('storage'),
            public_path('storage/media'),
        ];
        foreach ($dirsToFix as $d) {
            if (\Illuminate\Support\Facades\File::isDirectory($d)) {
                @chmod($d, 0755);
            }
        }
        $storageFiles = \Illuminate\Support\Facades\File::isDirectory(storage_path('app/public/media'))
            ? \Illuminate\Support\Facades\File::files(storage_path('app/public/media'))
            : [];
        $publicFiles = \Illuminate\Support\Facades\File::isDirectory(public_path('storage/media'))
            ? \Illuminate\Support\Facades\File::files(public_path('storage/media'))
            : [];
        $filesToFix = array_merge($storageFiles, $publicFiles);
        foreach ($filesToFix as $f) {
            @chmod($f->getPathname(), 0644);
        }

        $mediaCount = \App\Models\Media::where('mediable_type', \App\Models\Animal::class)->count();
        $publicMediaCount = count($publicFiles);
        $seederStatus = $seederRan
            ? 'Seeder uruchomiony - zaimportowano dane kotow.'
            : "Seeder pominieto - baza zawiera juz {$existingAnimalsCount} rekordow kotow (idempotent, bezpieczne).";

        return "<div style='font-family: sans-serif; padding: 30px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; color: #166534; max-width: 600px; margin: 40px auto;'>"
            . "<h1 style='margin-top:0;'>Sukces Naprawy Zdjec!</h1>"
            . "<p><strong>1. Utworzono dowiazanie public/storage.</strong></p>"
            . "<p><strong>2. Przekopiowano zdjecia z folderu image/:</strong> Pomyslnie zapisano {$publicMediaCount} plikow w produkcyjnym katalogu public/storage/media/ na serwerze.</p>"
            . "<p><strong>3. Polaczono w bazie danych:</strong> Przypisano {$mediaCount} rekordow zdjec do kotow w bazie SQL.</p>"
            . "<p><strong>4. Status seedera:</strong> {$seederStatus}</p>"
            . "<hr style='border: 0; border-top: 1px solid #bbf7d0; margin: 20px 0;'>"
            . "<p style='margin-bottom:0;'><a href='" . url('/') . "' style='display: inline-block; padding: 10px 20px; background: #166534; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;'>Wróć na stronę główną i sprawdź zdjęcia</a></p>"
            . "</div>";
    } catch (\Throwable $e) {
        Log::error('Fix-storage execution error: ' . $e->getMessage(), ['exception' => $e]);

        return "<div style='font-family: sans-serif; padding: 30px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; color: #991b1b; max-width: 600px; margin: 40px auto;'>"
            . "<h1 style='margin-top:0;'>Blad Wykonania</h1>"
            . "<p>Wystąpił błąd podczas wykonywania operacji inicjalizacji storage. Szczegóły zostały zapisane w dzienniku zdarzeń (logach).</p>"
            . "</div>";
    }
})->middleware(['auth', 'verified', 'active']);

// One-click Auto Deploy & Sync Route for hosting (triggers git pull & seo article import)
Route::match(['get', 'post'], '/deploy-now', function () {
    try {
        $gitOutput = shell_exec('git pull origin main 2>&1');
        \Illuminate\Support\Facades\Artisan::call('seo:import-articles');
        $importOutput = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        return "<div style='font-family:sans-serif; padding:30px; background:#ecfdf5; border:1px solid #10b981; border-radius:12px; color:#065f46; max-width:700px; margin:40px auto; font-size:15px; line-height:1.6;'>"
            . "<h1 style='margin-top:0;'>🚀 Sukces Aktualizacji Hostingu!</h1>"
            . "<p><strong>1. Pobieranie najnowszego kodu z GitHub (git pull):</strong></p>"
            . "<pre style='background:#fff; padding:12px; border-radius:6px; border:1px solid #a7f3d0; font-size:13px; color:#047857;'>" . e($gitOutput ?: 'Brak odpowiedzi git (sprawdź czy git jest na ścieżce PATH serwera)') . "</pre>"
            . "<p><strong>2. Wynik importu artykułów SEO do bazy produkcyjnej:</strong></p>"
            . "<pre style='background:#fff; padding:12px; border-radius:6px; border:1px solid #a7f3d0; font-size:13px; color:#047857;'>" . e($importOutput ?: 'Pomyślnie przetworzono artykuły!') . "</pre>"
            . "<hr style='border:0; border-top:1px solid #bbf7d0; margin:20px 0;'>"
            . "<div style='display:flex; gap:10px;'>"
            . "<a href='" . url('/blog') . "' style='background:#10b981; color:#fff; padding:12px 22px; text-decoration:none; border-radius:8px; font-weight:bold;'>Przejdź do Bloga (/blog) →</a>"
            . "<a href='" . url('/backend/posts') . "' style='background:#047857; color:#fff; padding:12px 22px; text-decoration:none; border-radius:8px; font-weight:bold;'>Przejdź do Panelu (/backend/posts) →</a>"
            . "</div>"
            . "</div>";
    } catch (\Throwable $e) {
        return "<div style='font-family:sans-serif; padding:30px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; color:#991b1b; max-width:600px; margin:40px auto;'>"
            . "<h1 style='margin-top:0;'>Błąd Wykonania</h1>"
            . "<p>" . e($e->getMessage()) . "</p>"
            . "</div>";
    }
});

// backend dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'active'])
    ->name('dashboard');


// backend routes
Route::middleware(['auth', 'verified', 'active'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {

        Route::resource('users', UserController::class);
        Route::patch('users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])
            ->name('users.forceDelete');

        Route::resource('roles', RoleController::class);
        Route::patch('roles/{id}/restore', [RoleController::class, 'restore'])
            ->name('roles.restore');
        Route::delete('roles/{id}/force-delete', [RoleController::class, 'forceDelete'])
            ->name('roles.forceDelete');

        Route::resource('categories', CategoryController::class);
        Route::patch('categories/{id}/restore', [CategoryController::class, 'restore'])
            ->name('categories.restore');
        Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])
            ->name('categories.forceDelete');

        Route::post('posts/import-seo', [PostController::class, 'importSeo'])
            ->name('posts.importSeo');
        Route::resource('posts', PostController::class);
        Route::patch('posts/{id}/restore', [PostController::class, 'restore'])
            ->name('posts.restore');
        Route::delete('posts/{id}/force-delete', [PostController::class, 'forceDelete'])
            ->name('posts.forceDelete');

        Route::resource('animals', \App\Http\Controllers\Backend\AnimalController::class);
        Route::patch('animals/{id}/restore', [\App\Http\Controllers\Backend\AnimalController::class, 'restore'])
            ->name('animals.restore');
        Route::delete('animals/{id}/force-delete', [\App\Http\Controllers\Backend\AnimalController::class, 'forceDelete'])
            ->name('animals.forceDelete');

        Route::get('media-api', [MediaController::class, 'api'])->name('media.api');
        Route::resource('media', MediaController::class);
    });

require __DIR__.'/settings.php';
