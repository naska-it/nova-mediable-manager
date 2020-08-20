<?php

namespace NaskaIt\NovaMediableManager;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use NaskaIt\NovaMediableManager\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-mediable-manager');

        $this->app->booted(function () {
            $this->routes();
        });

        if (! class_exists('CreateMediaTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_media_table.stub' => database_path(
                    'migrations/'.date('Y_m_d_His', time()).'_create_media_table.php'
                ),
            ], 'migrations');
        }

        $this->publishes([
            __DIR__.'/../config/nova-mediable-manager.php' => config_path('nova-mediable-manager.php')
        ], 'nova-mediable-manager');

        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'novaMediableManager' => $this->config(),
            ]);
        });
    }

    private function config()
    {
        $config =  config('nova-mediable-manager');

        if (is_array($config['types'])) {
            $accept = [];

            foreach ($config['types'] as $key) {
                $accept = array_merge($accept, $key);
            }

            if (in_array('*', $accept)) {
                $accept = [];
            }

            $config['accept'] = implode(',', $accept);
            $config['types'] = array_keys($config['types']);
        }

        return $config;
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/nova-mediable-manager')
                ->namespace('NaskaIt\NovaMediableManager\Http\Controllers')
                ->group(__DIR__.'/../routes/api.php');
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
}
