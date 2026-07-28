<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryIndexRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryIndexRequest $request)
    {
        $filters = $request->defaults();

        $categories = Category::query()
//            ->withCount('posts')
            ->search($filters['q'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('backend.categories.index', [
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
        return view('backend.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('backend.categories.index')
                ->with('success', "Category '{$category->name}' created successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Category could not be created. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
//        $category->loadCount('posts');

        return view('backend.categories.show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('backend.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $category->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('backend.categories.edit', $category)
                ->with('success', "Category '{$category->name}' updated successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Category could not be updated. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()
                ->route('backend.categories.index')
                ->with('success', "Category '{$category->name}' deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Category could not be deleted.');
        }
    }

    public function restore(int $id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);

            $category->restore();

            return redirect()
                ->route('backend.categories.index')
                ->with('success', "Category '{$category->name}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Category could not be restored.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);

            if ($category->posts()->exists()) {
                return back()->with('error', 'Category cannot be permanently deleted because posts are still linked to it.');
            }

            $name = $category->name;

            $category->forceDelete();

            return redirect()
                ->route('backend.categories.index')
                ->with('success', "Category '{$name}' permanently deleted.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Category could not be permanently deleted.');
        }
    }
}
