<?php

namespace App\Providers;

use App\Models\User; // Import model User
use Illuminate\Support\Facades\Gate; // Import Gate
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // Mendefinisikan gate 'admin' agar middleware can:admin berfungsi
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}