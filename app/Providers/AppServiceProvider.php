<?php

namespace App\Providers;

use App\Models\FormSubmission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (request()->header('X-Forwarded-Proto') === 'https' || request()->isSecure()) {
            URL::forceScheme('https');
        }

        Gate::define('submit-form', function ($user) {
            return !FormSubmission::where('user_id', $user->id)->exists();
        });

        Gate::define('is-admin', function ($user) {
            return $user->email === 'luismiortegasancho@gmail.com';
        });
    }
}
