<?php

namespace App\Providers;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class GooglePubSubProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PubSubClient::class, function (Application $app) {
            $fileContent = file_get_contents(
                config('gcp.google_application_credentials')
            );

            $keyFile = json_decode($fileContent, true);

            return new PubSubClient([
                'projectId' => config('gcp.google_cloud_project'),
                'keyFile' => $keyFile
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
