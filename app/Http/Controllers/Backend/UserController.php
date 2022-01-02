<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Interface\Backend\UserInterface;

class UserController extends Controller
{
    public $userRepo;

    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('admin.users.access');

        if($request->has('archive')) {
            $isTrashed = true;
        }else {
            $isTrashed = false;
        }

        $users = $this->userRepo->getAllUsers($request);
        return view('backend.users.index', compact('users', 'isTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('admin.users.create');
        $roles = $this->userRepo->getAllRoles();
        return view('backend.users.form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        Gate::authorize('admin.users.create');
        $this->userRepo->storeUpdateUser($request);
        notify()->success("User created","Success","topCenter");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Gate::authorize('admin.users.access');
        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        Gate::authorize('admin.users.edit');
        $roles = $this->userRepo->getAllRoles();
        return view('backend.users.form', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('admin.users.edit');
        $this->userRepo->storeUpdateUser($request, $user);
        notify()->success("User updated","Success","topCenter");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Gate::authorize('admin.users.destroy');
        if ($user->deletable) {
            $user->delete();
            notify()->success("User deleted","Success","topCenter");
        } else {
            notify()->error("You can\'t delete system user.","Error","topCenter");
        }
        return back();
    }

    /**
     * Restore delete data from database
     *
     * @param  mixed $id
     * @return void
     */
    public function restore($id)
    {
        Gate::authorize('admin.users.destroy');
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        notify()->success("User restore","Success","topCenter");
        return back();
    }

    /**
     * permanently delete from database
     *
     * @param  mixed $id
     * @return void
     */
    public function forceDelete($id)
    {
        Gate::authorize('admin.users.destroy');
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        notify()->success("User permanently deleted","Success","topCenter");
        return back();
    }
}
