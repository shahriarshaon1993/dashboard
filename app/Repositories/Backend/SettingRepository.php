<?php

namespace App\Repositories\Backend;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Interface\Backend\SettingInterface;

class SettingRepository implements SettingInterface
{
    /**
     * Store and update general setting
     *
     * @param  mixed $request
     * @return void
     */
    public function general($request)
    {
        $datas = $this->ignoreSpace($request->all());

        Setting::updateOrCreate(['name' => 'site_title'], ['value' => $datas['site_title']]);
        // .env file update
        Artisan::call("env:set APP_NAME='" . $datas['site_title'] . "'");

        Setting::updateOrCreate(['name' => 'site_description'], ['value' => $request->get('site_description')]);
        Setting::updateOrCreate(['name' => 'site_address'], ['value' => $request->get('site_address')]);
    }

    /**
     * Store and update appearance setting
     *
     * @param  mixed $request
     * @return void
     */
    public function appearance($request)
    {
        // Update logo
        if ($request->hasFile('site_logo')) {
            $this->deleteOldLogo(setting('site_logo'));
            Setting::updateOrCreate(['name' => 'site_logo'],[
                    'value' => Storage::disk('public')->putFile('logo', $request->file('site_logo'))
                ]
            );
        }

        // Update favicon
        if ($request->hasFile('site_favicon')) {
            $this->deleteOldLogo(setting('site_favicon'));
            Setting::updateOrCreate(['name' => 'site_favicon'], [
                    'value' => Storage::disk('public')->putFile('logo', $request->file('site_favicon'))
                ]
            );
        }
    }

     /**
     * When logo is updated, old logo image automaticaly deleted
     *
     * @param  mixed $path
     * @return void
     */
    private function deleteOldLogo($path)
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * Store and update mail setting
     *
     * @param  mixed $request
     * @return void
     */
    public function mail($request)
    {
        $datas = $this->ignoreSpace($request->all());

        Setting::updateOrCreate(['name' => 'mail_mailer'], ['value' => $datas['mail_mailer']]);
        Artisan::call("env:set MAIL_MAILER='" . $datas['mail_mailer'] . "'");

        Setting::updateOrCreate(['name' => 'mail_host'], ['value' => $datas['mail_host']]);
        Artisan::call("env:set MAIL_HOST='" . $datas['mail_host'] . "'");

        Setting::updateOrCreate(['name' => 'mail_port'], ['value' => $datas['mail_port']]);
        Artisan::call("env:set MAIL_PORT='" . $datas['mail_port'] . "'");

        Setting::updateOrCreate(['name' => 'mail_username'], ['value' => $datas['mail_username']]);
        Artisan::call("env:set MAIL_USERNAME='" . $datas['mail_username'] . "'");

        Setting::updateOrCreate(['name' => 'mail_password'], ['value' => $datas['mail_password']]);
        Artisan::call("env:set MAIL_PASSWORD='" . $datas['mail_password'] . "'");

        Setting::updateOrCreate(['name' => 'mail_encryption'], ['value' => $datas['mail_encryption']]);
        Artisan::call("env:set MAIL_ENCRYPTION='" . $datas['mail_encryption'] . "'");

        Setting::updateOrCreate(['name' => 'mail_from_address'], ['value' => $datas['mail_from_address']]);
        Artisan::call("env:set MAIL_FROM_ADDRESS='" . $datas['mail_from_address'] . "'");

        Setting::updateOrCreate(['name' => 'mail_from_name'], ['value' => $datas['mail_from_name']]);
        Artisan::call("env:set MAIL_FROM_NAME='" . $datas['mail_from_name'] . "'");
    }

    /**
     * ignore space for save .env file
     *
     * @param  mixed $datas
     * @return void
     */
    private function ignoreSpace($datas)
    {
        foreach($datas as $key=> $data) {
            $datas[$key] = trim(str_replace(' ', '', $data));
        }

        return $datas;
    }
}
