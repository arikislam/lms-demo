<?php

namespace Smariqislam\Coupon;
use Illuminate\Support\ServiceProvider;
use Smariqislam\Coupon\Classes\Coupon;


class CouponServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__.'/config/coupon.php' => config_path('coupon.php'),
        ], 'coupon-config');
    }


    public function register()
    {
        $this->app->bind('coupon', function($app) {
            return new Coupon();
        });
    }

}