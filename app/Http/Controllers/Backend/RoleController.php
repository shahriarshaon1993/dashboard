<?php

namespace App\Http\Controllers\Backend;

use Throwable;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Interface\Backend\RoleInterface;
use App\Http\Requests\Backend\StoreRoleRequest;
use App\Http\Requests\Backend\UpdateRoleRequest;

class RoleController extends Controller
{
    public $roleRepo;

    public function __construct(RoleInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('admin.roles.access');
        try {
            $roles = Role::withCount('permissions')->get();
            return view('backend.roles.index', compact('roles'));
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('admin.roles.create');
        try {
            $modules = $this->roleRepo->getAllModule();
            return view('backend.roles.form', compact('modules'));
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        Gate::authorize('admin.roles.create');
        try {
            Role::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ])->permissions()->sync($request->input('permissions', []));
            notify()->success("Role added","Success","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        Gate::authorize('admin.roles.edit');
        try {
            $modules = $this->roleRepo->getAllModule();
            return view('backend.roles.form', compact('modules', 'role'));
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('admin.roles.edit');
        try {
            $role->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            notify()->success("Role updated","Success","topCenter");
            $role->permissions()->sync($request->input('permissions', []));
            return redirect()->route('admin.roles.edit', $role->slug);
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        Gate::authorize('admin.roles.destroy');
        try {
            if ($role->deletable) {
                $role->delete();
                notify()->success("Role deleted","Success","topCenter");
            } else {
                notify()->error("You can\'t delete system role.","Error","topCenter");
            }
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }
}
