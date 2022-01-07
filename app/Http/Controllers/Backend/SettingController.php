<?php

namespace App\Http\Controllers\Backend;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Interface\Backend\SettingInterface;
use App\Http\Requests\Backend\UpdateMailSettingRequest;
use App\Http\Requests\Backend\UpdateGeneralSettingRequest;
use App\Http\Requests\Backend\UpdateAppearanceSettingRequest;

class SettingController extends Controller
{
    public $settingRepo;

    public function __construct(SettingInterface $settingRepo)
    {
        $this->settingRepo = $settingRepo;
    }

    /**
     * Show index page
     *
     * @return void
     */
    public function general()
    {
        Gate::authorize('admin.settings.access');
        return view('backend.settings.general');
    }

    /**
     * store and update general setting
     *
     * @param  mixed $request
     * @return void
     */
    public function generalUpdate(UpdateGeneralSettingRequest $request)
    {
        Gate::authorize('admin.settings.access');

        try {
            $this->settingRepo->general($request);
            notify()->success("Settings updated","Success","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }
    }

    /**
     * Show appearance page
     *
     * @return void
     */
    public function appearance()
    {
        Gate::authorize('admin.settings.access');
        return view('backend.settings.appearance');
    }

    /**
     * Store and update appearance settings
     *
     * @param  mixed $request
     * @return void
     */
    public function appearanceUpdate(UpdateAppearanceSettingRequest $request)
    {
        Gate::authorize('admin.settings.access');

        try {
            $this->settingRepo->appearance($request);
            notify()->success("Settings updated","Success","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }

    }

    /**
     * show mail index
     *
     * @return void
     */
    public function mail()
    {
        Gate::authorize('admin.settings.access');
        return view('backend.settings.mail');
    }

    /**
     * Store and update mail settings
     *
     * @return void
     */
    public function mailUpdate(UpdateMailSettingRequest $request)
    {
        Gate::authorize('admin.settings.access');

        try{
            $this->settingRepo->mail($request);
            notify()->success("Settings updated","Success","topCenter");
            return back();
        }catch (Throwable $exception) {
            report($exception);
            notify()->error($exception->getMessage(),"Error","topCenter");
            return back();
        }

    }
}
