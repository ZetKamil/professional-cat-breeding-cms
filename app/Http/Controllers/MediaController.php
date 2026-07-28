<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaIndexRequest;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MediaController extends Controller
{
    public function index(MediaIndexRequest $request)
    {
        $filters = $request->defaults();

        $media = Media::query()
            ->with('mediable')
            ->search($filters['q'])
            ->typeFilter($filters['type'])
            ->featuredFilter($filters['featured'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('backend.media.index', [
            'media' => $media,
            'filters' => $filters,
            'perPageAllowed' => [10, 25, 50, 100],
        ]);
    }

    public function create()
    {
        $posts = Post::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.media.create', [
            'posts' => $posts,
            'users' => $users,
            'prefillType' => request('type'),
            'prefillId' => request('id'),
        ]);
    }

    public function store(StoreMediaRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $file = $request->file('upload');

            $directory = match ($data['mediable_type'] ?? null) {
                Post::class => 'media/posts',
                User::class => 'media/users',
                default => 'media/unattached',
            };

            $storedPath = $file->store($directory, 'public');

            if (($data['is_featured'] ?? false) && ! empty($data['mediable_type']) && ! empty($data['mediable_id'])) {
                Media::query()
                    ->where('mediable_type', $data['mediable_type'])
                    ->where('mediable_id', $data['mediable_id'])
                    ->update(['is_featured' => false]);
            }

            $media = Media::create([
                'mediable_type' => $data['mediable_type'] ?? null,
                'mediable_id' => $data['mediable_id'] ?? null,
                'disk' => 'public',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $storedPath,
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'sort_order' => $data['sort_order'],
                'is_featured' => $data['is_featured'],
            ]);

            DB::commit();

            return redirect()
                ->route('backend.media.index')
                ->with('success', "Media '{$media->file_name}' created successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Media could not be created. Please try again.');
        }
    }

    public function show(Media $media)
    {
        $media->load('mediable');

        return view('backend.media.show', [
            'media' => $media,
        ]);
    }

    public function edit(Media $media)
    {
        $media->load('mediable');

        $posts = Post::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.media.edit', [
            'media' => $media,
            'posts' => $posts,
            'users' => $users,
            'prefillType' => null,
            'prefillId' => null,
        ]);
    }

    public function update(UpdateMediaRequest $request, Media $media)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $newPath = $media->file_path;
            $newName = $media->file_name;
            $newMime = $media->mime_type;
            $newSize = $media->file_size;

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');

                $directory = match ($data['mediable_type'] ?? null) {
                    Post::class => 'media/posts',
                    User::class => 'media/users',
                    default => 'media/unattached',
                };

                $storedPath = $file->store($directory, 'public');

                if ($media->file_path && Storage::disk($media->disk)->exists($media->file_path)) {
                    Storage::disk($media->disk)->delete($media->file_path);
                }

                $newPath = $storedPath;
                $newName = $file->getClientOriginalName();
                $newMime = $file->getClientMimeType();
                $newSize = $file->getSize();
            }

            if (($data['is_featured'] ?? false) && ! empty($data['mediable_type']) && ! empty($data['mediable_id'])) {
                Media::query()
                    ->where('mediable_type', $data['mediable_type'])
                    ->where('mediable_id', $data['mediable_id'])
                    ->where('id', '!=', $media->id)
                    ->update(['is_featured' => false]);
            }

            $media->update([
                'mediable_type' => $data['mediable_type'] ?? null,
                'mediable_id' => $data['mediable_id'] ?? null,
                'disk' => 'public',
                'file_name' => $newName,
                'file_path' => $newPath,
                'mime_type' => $newMime,
                'file_size' => $newSize,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'sort_order' => $data['sort_order'],
                'is_featured' => $data['is_featured'],
            ]);

            DB::commit();

            return redirect()
                ->route('backend.media.edit', $media)
                ->with('success', "Media '{$media->file_name}' updated successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Media could not be updated. Please try again.');
        }
    }

    public function destroy(Media $media)
    {
        try {
            $media->delete();

            return redirect()
                ->route('backend.media.index')
                ->with('success', "Media '{$media->file_name}' deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Media could not be deleted.');
        }
    }

    public function restore(int $id)
    {
        try {
            $media = Media::withTrashed()->findOrFail($id);

            $media->restore();

            return redirect()
                ->route('backend.media.index')
                ->with('success', "Media '{$media->file_name}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Media could not be restored.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $media = Media::withTrashed()->findOrFail($id);

            $name = $media->file_name;

            if ($media->file_path && Storage::disk($media->disk)->exists($media->file_path)) {
                Storage::disk($media->disk)->delete($media->file_path);
            }

            $media->forceDelete();

            return redirect()
                ->route('backend.media.index')
                ->with('success', "Media '{$name}' permanently deleted.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Media could not be permanently deleted.');
        }
    }
}
