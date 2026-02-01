<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Laravel;

use Illuminate\Support\ServiceProvider;
use Tigusigalpa\TAAPI\TAAPIClient;

class TAAPIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/taapi.php',
            'taapi'
        );

        $this->app->singleton(TAAPIClient::class, function ($app) {
            $apiSecret = config('taapi.api_secret');

            if (empty($apiSecret)) {
                throw new \RuntimeException(
                    'TAAPI API secret is not configured. Please set TAAPI_SECRET in your .env file.'
                );
            }

            $client = new TAAPIClient($apiSecret);

            if ($timeout = config('taapi.timeout')) {
                $client->setTimeout((int) $timeout);
            }

            return $client;
        });

        $this->app->alias(TAAPIClient::class, 'taapi');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/taapi.php' => config_path('taapi.php'),
            ], 'taapi-config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [TAAPIClient::class, 'taapi'];
    }
}
