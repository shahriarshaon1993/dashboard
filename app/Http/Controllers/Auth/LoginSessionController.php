<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;

final class LoginSessionController extends AuthenticatedSessionController
{
    public function store(LoginRequest $request): mixed
    {
        return $this->loginPipeline($request)->then(function (LoginRequest $request): LoginResponse {
            $user = Auth::user();

            if ($user?->is_active->value !== 'active') {
                Auth::logout();

                throw ValidationException::withMessages([
                    'email' => __('Your account is inactive. Please contact administrator.'),
                ]);
            }

            return app(LoginResponse::class);
        });
    }
}
