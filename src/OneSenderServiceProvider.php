<?php

namespace Pasya\OneSenderLaravel;

use Pasya\OneSenderLaravel\OneSender;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\ChannelManager;

class OneSenderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OneSender::class, function ($app) {
            $apiUrl = config('onesender.base_api_url') . '/api/v1/messages';
            $apiKey = config('onesender.api_key');

            return new OneSender($apiUrl, $apiKey);
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('onesender', function ($app) {
                return $app->get(OneSender::class);
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/onesender.php' => config_path('onesender.php'),
        ]);
    }
}
