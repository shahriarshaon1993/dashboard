<?php

namespace App\Http\Controllers\Backend;

use Throwable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UpdateProfileRequest;
use App\Http\Requests\Backend\UpdatePasswordRequest;
use App\Interface\Backend\ProfileInterface;

class ProfileController extends Controller
{
    public $profileRepo;

    public function __construct(ProfileInterface $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }
    /**
     * Show the authenticate user profile
     *
     * @return void
     */
    public function index()
    {
        return view('backend.profiles.index');
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $this->profileRepo->updateProfileInformation($request);
            notify()->success("Profile updated","Success","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Change authorize user view.
     *
     * @return void
     */
    public function changePassword()
    {
        return view('backend.profiles.password');
    }

    /**
     * Update authorize user password.
     *
     * @return void
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $this->profileRepo->updateProdilePassword($request);
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }

    }
}
