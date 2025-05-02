<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SsoContextServiceProvider extends ServiceProvider
{
    protected $userData = null;

    public function register(): void
    {
        // Register the singleton instance of this provider
        $this->app->singleton(SsoContextServiceProvider::class, function ($app) {
            return $this;
        });
    }

    public function boot(): void
    {
        // Share user data with all views
        View::composer('*', function ($view) {
            $view->with('ssoUser', $this->userData);
        });
    }

    public function setUserData($userData): void
    {
        $this->userData = $userData;

        // Set in view composer for immediate access
        View::share('ssoUser', $userData);
    }

    public function getUserData()
    {
        return $this->userData;
    }

    public function getUserAttribute(string $attribute, $default = null)
    {
        return $this->userData && isset($this->userData->{$attribute}) 
            ? $this->userData->{$attribute} 
            : $default;
    }
}