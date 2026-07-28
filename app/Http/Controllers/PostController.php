<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Throwable;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PostIndexRequest $request)
    {
        $this->authorize('viewAny', Post::class);

        $filters = $request->defaults();

        $postsQuery = Post::query()
            ->with(['user', 'creator', 'editor', 'categories', 'media'])
            ->search($filters['q'])
            ->authorFilter($filters['author'])
            ->categoryFilter($filters['category'])
            ->statusFilter($filters['status'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir']);

        if (! $this->canManageAllPosts()) {
            $postsQuery->where('user_id', auth()->id());
        }

        $posts = $postsQuery
            ->paginate($filters['per_page'])
            ->withQueryString();

        $authorsQuery = User::query()->orderBy('name');

        if (! $this->canManageAllPosts()) {
            $authorsQuery->where('id', auth()->id());
        }

        $authors = $authorsQuery->get(['id', 'name']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.posts.index', [
            'posts' => $posts,
            'authors' => $authors,
            'categories' => $categories,
            'filters' => $filters,
            'perPageAllowed' => [10, 25, 50, 100],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Post::class);

        $authors = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.posts.create', [
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);

        $post = $this->postService->create($request->validated());

        return redirect()
            ->route('backend.posts.index')
            ->with('success', "Post '{$post->title}' created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post->load(['user', 'creator', 'editor', 'categories', 'media']);

        return view('backend.posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $post->load(['categories', 'media']);

        $authorsQuery = User::query()->orderBy('name');

        if (! $this->canManageAllPosts()) {
            $authorsQuery->where('id', auth()->id());
        }

        $authors = $authorsQuery->get(['id', 'name']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.posts.edit', [
            'post' => $post,
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post = $this->postService->update($post, $request->validated());

        return redirect()
            ->route('backend.posts.edit', $post)
            ->with('success', "Post '{$post->title}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        try {
            $post->delete();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', "Post '{$post->title}' deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Post could not be deleted.');
        }
    }

    /**
     * Restore a soft deleted post.
     */
    public function restore(int $id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);

            $this->authorize('restore', $post);

            $post->restore();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', "Post '{$post->title}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Post could not be restored.');
        }
    }

    /**
     * Permanently delete a post.
     */
    public function forceDelete(int $id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);

            $this->authorize('forceDelete', $post);

            $title = $post->title;

            $post->forceDelete();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', "Post '{$title}' permanently deleted.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Post could not be permanently deleted.');
        }
    }

    protected function canManageAllPosts(): bool
    {
        return in_array(auth()->user()?->role?->name, ['admin', 'editor'], true);
    }
}
