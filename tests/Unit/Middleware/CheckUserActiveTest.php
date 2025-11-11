<?php

declare(strict_types=1);

use App\Enums\ActiveStatus;
use App\Http\Middleware\CheckUserActive;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

it('middleware redirects unauthenticated users', function (): void {
    $middleware = new CheckUserActive();
    $request = Request::create(route('dashboard'), 'GET');

    $response = $middleware->handle($request, fn ($request): Illuminate\Http\Response => new Illuminate\Http\Response('Success'));

    $this->assertEquals(302, $response->getStatusCode());
});

it('middleware redirects unauthenticated inactive users and logs them out', function (): void {
    $user = User::factory()->withoutTwoFactor()->create([
        'is_active' => ActiveStatus::Inactive->value,
    ]);

    Auth::login($user);

    $middleware = new CheckUserActive();
    $request = Request::create(route('dashboard'), 'GET');

    $response = $middleware->handle($request, fn ($request): Illuminate\Http\Response => new Illuminate\Http\Response('Success'));

    $this->assertEquals(302, $response->getStatusCode());
});

it('middleware allows authenticated active users', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    Auth::login($user);

    $middleware = new CheckUserActive();
    $request = Request::create(route('dashboard'), 'GET');

    $response = $middleware->handle($request, fn ($request): Illuminate\Http\Response => new Illuminate\Http\Response('Success'));

    $this->assertEquals(200, $response->getStatusCode());
});
