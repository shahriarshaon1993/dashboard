<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BulkDeleteUser;
use App\Actions\CreateUser;
use App\Actions\DeleteUser;
use App\Actions\GetUsers;
use App\Actions\UpdateUser;
use App\DTOs\BulkDestroyDto;
use App\DTOs\FilterDto;
use App\DTOs\UserDto;
use App\Enums\ActiveStatus;
use App\Exports\UserExport;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Imports\UserImport;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request, GetUsers $action): Response
    {
        Gate::authorize('viewAny', User::class);

        $filters = FilterDto::from($request->validatedFilters());

        $data = $action->handle($filters);

        return Inertia::render('users/Index', [
            'users' => $data['users'],
            'filters' => $data['filters'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', User::class);

        $modules = Module::query()
            ->with('permissions')
            ->orderBy('name', 'ASC')
            ->get();

        $roles = Role::query()
            ->orderBy('name', 'ASC')
            ->get();

        return Inertia::render('users/Create', [
            'roles' => RoleResource::collection($roles),
            'modules' => ModuleResource::collection($modules),
            'activeStatus' => collect(ActiveStatus::cases())
                ->map(fn (ActiveStatus $status): array => [
                    'value' => $status->value,
                    'label' => $status->label(),
                ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUser $action): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $userDto = UserDto::from($request);

        $action->handle($userDto);

        return to_route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        $modules = Module::query()
            ->with('permissions')
            ->orderBy('name', 'ASC')
            ->get();

        $roles = Role::query()
            ->orderBy('name', 'ASC')
            ->get();

        $user->load(['roles', 'permissions', 'media']);

        return Inertia::render('users/Edit', [
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($roles),
            'modules' => ModuleResource::collection($modules),
            'activeStatus' => collect(ActiveStatus::cases())
                ->map(fn (ActiveStatus $status): array => [
                    'value' => $status->value,
                    'label' => $status->label(),
                ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUser $action): RedirectResponse
    {
        Gate::authorize('update', $user);

        $userDto = UserDto::from($request);

        $action->handle($userDto, $user);

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUser $action): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $action->handle($user);

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(DeleteRequest $request, BulkDeleteUser $action): RedirectResponse
    {
        Gate::authorize('delete', User::class);

        /** @var array{ids: int[]} $data */
        $data = $request->validated();

        $userIds = BulkDestroyDto::from($data);

        $action->handle($userIds->ids);

        return back()->with('success', 'Selected users deleted successfully.');
    }

    /**
     * Import users from an Excel file.
     */
    public function import(Request $request): RedirectResponse
    {
        Gate::authorize('import', User::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv'],
        ]);

        Excel::import(new UserImport(), $request->file('file'));

        return back()->with('success', 'Users imported successfully!');
    }

    /**
     * Export users to an Excel file.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', User::class);

        $ids = (array) $request->input('ids', []);

        /** @var int[] $ids */
        $ids = isset($ids['ids']) ? (array) $ids['ids'] : $ids;

        return Excel::download(new UserExport($ids), 'users.xlsx');
    }
}
