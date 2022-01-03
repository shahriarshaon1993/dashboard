<?php

namespace App\Interface\Backend;

interface ProfileInterface
{
    public function updateProfileInformation($request);
    public function updateProdilePassword($request);
}