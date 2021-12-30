<?php

namespace App\Repositories\Backend;

use App\Interface\Backend\RoleInterface;
use App\Models\Module;

class RoleRepository implements RoleInterface
{
    public function getAllModule()
    {
        return Module::with('permissions')->get();
    }
}