<?php

namespace App\Interface\Backend;

interface SettingInterface
{
    public function general($request);
    public function appearance($request);
    public function mail($request);
}
