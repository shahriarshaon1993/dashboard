<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        Gate::authorize('access-dashboard');

        return Inertia::render('Dashboard');
    }
}
