<?php

namespace App\Providers;

use Braintree\Gateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    ##ignetto payment
    public function boot(): void
    {

        $this->app->singleton(Gateway::class, function($app){
            return new Gateway(
                [
                    'environment' => 'sandbox',
                    'merchantId' => 'pf6gm5q5z5qdj6nw',
                    'publicKey' => '6wvmmqk25nnnkzpm',
                    'privateKey' => '650edb546863d1d4913a763f02afa6c0'
                  ]
                );
        });
    }
}
