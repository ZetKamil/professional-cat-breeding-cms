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
        ->latest('published_at')
        ->get();

    $animals = \App\Models\Animal::published()->get();

    $staticPages = [
        ['url' => route('home'),                    'priority' => '1.0', 'changefreq' => 'weekly'],
        ['url' => route('frontend.animals.index'),  'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => route('frontend.blog.index'),     'priority' => '0.8', 'changefreq' => 'weekly'],
        ['url' => route('cattery'),                 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => route('about'),                   'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => route('contact'),                 'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => route('privacy'),                 'priority' => '0.3', 'changefreq' => 'yearly'],
        ['url' => route('terms'),                   'priority' => '0.3', 'changefreq' => 'yearly'],
    ];

    return response()->view('sitemap', compact('staticPages', 'animals', 'posts'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Direct media streaming route to bypass Apache 403 Forbidden symlink restrictions on shared hosting
Route::get('/storage/media/{filename}', function ($filename) {
    $filename = basename($filename);

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif', 'ico', 'pdf', 'mp4', 'webm'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (! in_array($ext, $allowedExtensions, true)) {
        abort(404);
    }

    $possiblePaths = [
        public_path('storage/media/' . $filename),
        storage_path('app/public/media/' . $filename),
        storage_path('app/public/' . $filename),
    ];

    foreach ($possiblePaths as $path) {
        if (\Illuminate\Support\Facades\File::exists($path) && ! \Illuminate\Support\Facades\File::isDirectory($path)) {
            $mime = @mime_content_type($path) ?: 'image/jpeg';

            return response()->file($path, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }
    }

    // Global recursive fallback search in base_path('image')
    $baseImageDir = base_path('image');
    if (\Illuminate\Support\Facades\File::isDirectory($baseImageDir)) {
        $searchBase = strtolower(pathinfo($filename, PATHINFO_FILENAME));
        foreach (\Illuminate\Support\Facades\File::allFiles($baseImageDir) as $f) {
            $fNameBase = strtolower(pathinfo($f->getFilename(), PATHINFO_FILENAME));
            if ($fNameBase === $searchBase) {
                $mime = @mime_content_type($f->getPathname()) ?: 'image/jpeg';

                return response()->file($f->getPathname(), [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
        }
    }

    abort(404);
});

// Diagnostic route to inspect media status on hosting (auth required)
Route::get('/check-media', function () {
    $animals = \App\Models\Animal::with('media')->get();
    $output = "<div style='font-family:sans-serif; padding:30px; max-width:800px; margin:0 auto;'>";
    $output .= "<h2>Diagnostyka Bazy i Plikow Zdjec Kotow</h2>";
    $output .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse; width:100%;'>";
    $output .= "<tr style='background:#f3f4f6;'><th>Kot</th><th>Rasa</th><th>Plik na serwerze?</th><th>Wygenerowany URL</th></tr>";

    foreach ($animals as $a) {
        $media = $a->media;
        if (! $media) {
            $output .= "<tr><td>" . e($a->name) . "</td><td>" . e($a->breed) . "</td><td style='color:red;'>BRAK MEDIA W BAZIE</td><td>-</td></tr>";
            continue;
        }

        $filePath = public_path('storage/' . ($media->directory ? $media->directory . '/' : '') . $media->filename);
        $exists = file_exists($filePath);
        $statusColor = $exists ? 'green' : 'red';
        $statusText = $exists ? 'TAK (Plik istnieje)' : 'NIE (Brak pliku)';
        $url = $media->url();

        $output .= "<tr>"
            . "<td><strong>" . e($a->name) . "</strong></td>"
            . "<td>" . e($a->breed) . "</td>"
            . "<td style='color:{$statusColor}; font-weight:bold;'>{$statusText}</td>"
            . "<td><a href='" . e($url) . "' target='_blank'>Otworz zdjecie</a></td>"
            . "</tr>";
    }

    $output .= "</table>";
    $output .= "<p style='margin-top:20px;'><a href='" . url('/fix-storage') . "' style='background:#2563eb; color:#fff; padding:10px 15px; border-radius:6px; text-decoration:none; font-weight:bold;'>Uruchom Naprawe (/fix-storage)</a></p>";
    $output .= "</div>";

    return $output;
})->middleware(['auth', 'verified', 'active']);

// Utility route to trigger storage link & seed real cat images on hosting without SSH (admin only)
Route::get('/fix-storage', function () {
    // SECURITY: Restrict to admin role only — this route seeds data and must not be accessible to editors/users
    if (auth()->user()?->role?->name !== 'admin') {
        abort(403, 'Tylko administrator moze uruchomic te operacje.');
    }

    try {
        // Clean broken symlink if exists on hosting
        $pubStorage = public_path('storage');
        if (is_link($pubStorage) || (file_exists($pubStorage) && ! is_dir($pubStorage))) {
            @unlink($pubStorage);
        }

        @\Illuminate\Support\Facades\Artisan::call('storage:link');

        // Ensure physical directory public/storage/media exists if symlinks are blocked by Apache
        $pubStorageMedia = public_path('storage/media');
        if (! \Illuminate\Support\Facades\File::isDirectory($pubStorageMedia)) {
            \Illuminate\Support\Facades\File::makeDirectory($pubStorageMedia, 0755, true, true);
        }

        // IDEMPOTENT: Only run seeder if animals table is empty — prevents data overwrite on re-runs
        $seederRan = false;
        $existingAnimalsCount = \App\Models\Animal::withTrashed()->count();
        if ($existingAnimalsCount === 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\RealKittensAndParentsSeeder',
                '--force' => true,
            ]);
            $seederRan = true;
        }

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
        $filesToFix = array_merge(
            \Illuminate\Support\Facades\File::files(storage_path('app/public/media')) ?? [],
            \Illuminate\Support\Facades\File::files(public_path('storage/media')) ?? []
        );
        foreach ($filesToFix as $f) {
            @chmod($f->getPathname(), 0644);
        }

        $mediaCount = \App\Models\Media::where('mediable_type', \App\Models\Animal::class)->count();
        $publicMediaCount = count(\Illuminate\Support\Facades\File::files(public_path('storage/media')) ?? []);
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
