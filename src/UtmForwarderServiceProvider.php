<?php

namespace GuiAssemany\UtmForwarder;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;

class UtmForwarderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/utm-forwarder.php' => config_path('utm-forwarder.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/utm-forwarder.php', 'utm-forwarder');

        $this->app->singleton(AnalyticsBag::class, function ($app) {
            return new AnalyticsBag(
                $app->make(Session::class),
                config('utm-forwarder.tracked_parameters'),
                config('utm-forwarder.session_key'),
            );
        });
    }
}
