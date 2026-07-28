<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserIndexRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $filters = $request->defaults();

        $users = User::query()
            ->with(['role', 'media'])
            ->search($filters['q'])
            ->roleFilter($filters['role'])
            ->statusFilter($filters['status'])
            ->verifiedFilter($filters['verified'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        $roles = Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.users.index', [
            'users' => $users,
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
        $this->authorize('create', User::class);

        $roles = Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.users.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();

        try {
            DB::beginTransaction();

            $data['password'] = Hash::make($data['password']);

            if (! empty($data['verified']) && empty($data['email_verified_at'])) {
                $data['email_verified_at'] = now();
            }

            unset($data['verified']);

            $user = User::create($data);

            if ($request->hasFile('image')) {
                $this->mediaService->upload(
                    $user,
                    $request->file('image'),
                    'users'
                );
            }

            DB::commit();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' created successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'User could not be created. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load(['role', 'media']);

        return view('backend.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $user->load('media');

        return view('backend.users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        try {
            DB::beginTransaction();

            if (! empty($data['verified']) && empty($data['email_verified_at'])) {
                $data['email_verified_at'] = $user->email_verified_at ?? now();
            }

            if (array_key_exists('verified', $data)) {
                unset($data['verified']);
            }

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            if ($request->hasFile('image')) {
                $this->mediaService->replace(
                    $user,
                    $request->file('image'),
                    'users'
                );
            }

            DB::commit();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' updated successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'User could not be updated. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            $user->delete();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'User could not be deleted.');
        }
    }

    public function restore(int $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            $this->authorize('restore', $user);

            $user->restore();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'User could not be restored.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            $this->authorize('forceDelete', $user);

            $name = $user->name;

            $user->forceDelete();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$name}' permanently deleted.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'User could not be permanently deleted.');
        }
    }
}
