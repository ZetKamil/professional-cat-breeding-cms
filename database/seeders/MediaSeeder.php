<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $disk = 'public';

        Storage::disk($disk)->makeDirectory('users');
        Storage::disk($disk)->makeDirectory('posts');

        $this->seedUserImages($disk);
        $this->seedPostImages($disk);
    }

    private function seedUserImages($disk)
    {
        $users = User::all();

        foreach ($users as $user) {

            $filename = Str::uuid().'.jpg';

            $path = 'users/'.$filename;

            Storage::disk($disk)->put(
                $path,
                file_get_contents('https://picsum.photos/300')
            );

            $media = new Media([
                'disk' => $disk,
                'directory' => 'users',
                'filename' => $filename,
                'mime_type' => 'image/jpeg',
                'size' => Storage::disk($disk)->size($path),
            ]);

            $user->media()->save($media);
        }
    }

    private function seedPostImages($disk)
    {
        $posts = Post::all();

        foreach ($posts as $post) {

            $filename = Str::uuid().'.jpg';

            $path = 'posts/'.$filename;

            Storage::disk($disk)->put(
                $path,
                file_get_contents('https://picsum.photos/600')
            );

            $media = new Media([
                'disk' => $disk,
                'directory' => 'posts',
                'filename' => $filename,
                'mime_type' => 'image/jpeg',
                'size' => Storage::disk($disk)->size($path),
            ]);

            $post->media()->save($media);
        }
    }
}
