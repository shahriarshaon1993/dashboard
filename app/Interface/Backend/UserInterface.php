<?php

namespace App\Interface\Backend;

interface UserInterface
{
    public function getAllUsers($request);
    public function getAllRoles();
    public function storeUpdateUser($request, $user = null);
}