<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleIndexRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Throwable;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RoleIndexRequest $request)
    {
        $this->authorize('viewAny', Role::class);

        $filters = $request->defaults();

        $roles = Role::query()
            ->withCount('users')
            ->search($filters['q'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('backend.roles.index', [
            'roles' => $roles,
            'filters' => $filters,
            'perPageAllowed' => [10, 25, 50, 100],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        return view('backend.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $data = $request->validated();

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('backend.roles.index')
                ->with('success', "Role '{$role->name}' created successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Role could not be created. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        $role->load(['users' => function ($query) {
            $query->orderBy('name');
        }]);

        $role->loadCount('users');

        return view('backend.roles.show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        return view('backend.roles.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $request->validated();

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('backend.roles.edit', $role)
                ->with('success', "Role '{$role->name}' updated successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Role could not be updated. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        try {
            $role->delete();

            return redirect()
                ->route('backend.roles.index')
                ->with('success', "Role '{$role->name}' deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Role could not be deleted.');
        }
    }

    public function restore(int $id)
    {
        try {
            $role = Role::withTrashed()->findOrFail($id);

            $this->authorize('restore', $role);

            $role->restore();

            return redirect()
                ->route('backend.roles.index')
                ->with('success', "Role '{$role->name}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Role could not be restored.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $role = Role::withTrashed()->findOrFail($id);

            $this->authorize('forceDelete', $role);

            if ($role->users()->exists()) {
                return back()->with('error', 'Role cannot be permanently deleted because users are still linked to it.');
            }

            $name = $role->name;

            $role->forceDelete();

            return redirect()
                ->route('backend.roles.index')
                ->with('success', "Role '{$name}' permanently deleted.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Role could not be permanently deleted.');
        }
    }
}
