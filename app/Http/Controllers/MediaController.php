<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Media\DeleteMediaAction;
use App\Actions\Media\ReplaceMediaAction;
use App\Actions\Media\UploadMediaAction;
use App\Http\Requests\MediaIndexRequest;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Models\Animal;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class MediaController extends Controller
{
    public function __construct(
        protected UploadMediaAction $uploadMediaAction,
        protected ReplaceMediaAction $replaceMediaAction,
        protected DeleteMediaAction $deleteMediaAction,
    ) {}

    public function api(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Media::class);

        $media = Media::query()
            ->search($request->input('q'))
            ->typeFilter($request->input('type'))
            ->latest()
            ->paginate(24);

        $items = $media->map(fn (Media $m) => [
            'id' => $m->id,
            'url' => $m->url(),
            'filename' => $m->filename,
            'title' => $m->title ?: $m->filename,
            'alt_text' => $m->alt_text,
            'caption' => $m->caption,
            'is_image' => $m->isImage(),
            'size_kb' => round($m->size / 1024, 1),
        ]);

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    public function index(MediaIndexRequest $request): View
    {
        $this->authorize('viewAny', Media::class);

        $filters = $request->defaults();

        $media = Media::query()
            ->with('mediable')
            ->search($filters['q'])
            ->typeFilter($filters['type'])
            ->featuredFilter($filters['featured'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('backend.media.index', [
            'media' => $media,
            'filters' => $filters,
            'perPageAllowed' => [10, 24, 25, 48, 50, 100],
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Media::class);

        $posts = Post::query()->orderBy('title')->get(['id', 'title']);
        $users = User::query()->orderBy('name')->get(['id', 'name']);
        $animals = Animal::query()->orderBy('name')->get(['id', 'name']);

        return view('backend.media.create', [
            'posts' => $posts,
            'users' => $users,
            'animals' => $animals,
            'prefillType' => request('parent_type'),
            'prefillId' => (int) request('parent_id') ?: null,
        ]);
    }

    public function store(StoreMediaRequest $request): RedirectResponse
    {
        $this->authorize('create', Media::class);

        $data = $request->validated();
        $files = $request->hasFile('upload')
            ? [$request->file('upload')]
            : ($request->file('uploads', []));

        $media = $this->uploadMediaAction->handle($data, $files);
        $count = is_array($media) ? count($media) : 1;

        $message = $count > 1
            ? "Successfully uploaded {$count} media files."
            : 'Media created successfully.';

        return redirect()
            ->route('backend.media.index')
            ->with('success', $message);
    }

    public function show(Media $medium): View
    {
        $this->authorize('view', $medium);

        $medium->load('mediable');

        return view('backend.media.show', [
            'media' => $medium,
        ]);
    }

    public function edit(Media $medium): View
    {
        $this->authorize('update', $medium);

        $medium->load('mediable');

        $posts = Post::query()->orderBy('title')->get(['id', 'title']);
        $users = User::query()->orderBy('name')->get(['id', 'name']);
        $animals = Animal::query()->orderBy('name')->get(['id', 'name']);

        return view('backend.media.edit', [
            'media' => $medium,
            'posts' => $posts,
            'users' => $users,
            'animals' => $animals,
            'prefillType' => null,
            'prefillId' => null,
        ]);
    }

    public function update(UpdateMediaRequest $request, Media $medium): RedirectResponse
    {
        $this->authorize('update', $medium);

        $data = $request->validated();

        if ($request->hasFile('upload')) {
            $this->replaceMediaAction->handle($medium, $request->file('upload'), $data);
        } else {
            $medium->update([
                'mediable_type' => $data['mediable_type'] ?? null,
                'mediable_id' => $data['mediable_id'] ?? null,
                'title' => $data['title'] ?? $medium->title,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'copyright' => $data['copyright'] ?? null,
                'sort_order' => (int) ($data['sort_order'] ?? $medium->sort_order),
                'is_featured' => (bool) ($data['is_featured'] ?? $medium->is_featured),
            ]);
        }

        return redirect()
            ->route('backend.media.edit', $medium)
            ->with('success', "Media '{$medium->filename}' updated successfully.");
    }

    public function destroy(Media $medium): RedirectResponse
    {
        $this->authorize('delete', $medium);

        $name = $medium->filename;
        $this->deleteMediaAction->handle($medium);

        return redirect()
            ->route('backend.media.index')
            ->with('success', "Media '{$name}' deleted successfully.");
    }
}
