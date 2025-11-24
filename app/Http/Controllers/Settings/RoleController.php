<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\BulkDeleteRole;
use App\Actions\CreateRole;
use App\Actions\DeleteRole;
use App\Actions\GetRoles;
use App\Actions\UpdateRole;
use App\DTOs\BulkDestroyDto;
use App\DTOs\FilterDto;
use App\DTOs\RoleDto;
use App\Exports\RoleExport;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Imports\RoleImport;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class RoleController
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request, GetRoles $action): Response
    {
        Gate::authorize('viewAny', Role::class);

        $filters = FilterDto::from($request->validatedFilters());

        $data = $action->handle($filters);

        return Inertia::render('settings/roles/Index', [
            'roles' => $data['roles'],
            'filters' => $data['filters'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Role::class);

        return Inertia::render('settings/roles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request, CreateRole $action): RedirectResponse
    {
        Gate::authorize('create', Role::class);

        $action->handle(RoleDto::from($request));

        return to_route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): Response
    {
        Gate::authorize('update', Role::class);

        $role->loadMissing(['permissions']);

        return Inertia::render('settings/roles/Edit', [
            'role' => new RoleResource($role),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role, UpdateRole $action): RedirectResponse
    {
        Gate::authorize('update', Role::class);

        $action->handle(RoleDto::from($request), $role);

        return to_route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, DeleteRole $action): RedirectResponse
    {
        Gate::authorize('delete', Role::class);

        $action->handle($role);

        return to_route('roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(DeleteRequest $request, BulkDeleteRole $action): RedirectResponse
    {
        Gate::authorize('delete', Role::class);

        /** @var array{ids: int[]} $data */
        $data = $request->validated();

        $roleIds = BulkDestroyDto::from($data);

        $action->handle($roleIds->ids);

        return to_route('roles.index')->with('success', 'Selected roles deleted successfully.');
    }

    /**
     * Import roles from an Excel file.
     */
    public function import(Request $request): RedirectResponse
    {
        Gate::authorize('import', Role::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv'],
        ]);

        Excel::import(new RoleImport(), $request->file('file'));

        return to_route('roles.index')->with('success', 'Role imported successfully!');
    }

    /**
     * Export roles to an Excel file.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Role::class);

        $ids = (array) $request->input('ids', []);

        /** @var int[] $ids */
        $ids = isset($ids['ids']) ? (array) $ids['ids'] : $ids;

        return Excel::download(new RoleExport($ids), 'roles.xlsx');
    }
}
