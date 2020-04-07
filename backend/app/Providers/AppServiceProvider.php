<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Avdevs\Keycloak\KeycloakProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->bootKeycloakSocialite();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * bootKeycloakSocialite func
     *
     */
    private function bootKeycloakSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'keycloak',
            function ($app) use ($socialite) {
                $config = $app['config']['services.keycloak'];
                return new KeycloakProvider($config);
            }
        );
    }
}
