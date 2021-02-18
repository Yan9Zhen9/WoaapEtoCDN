<?php

namespace Yan9\Etocdn;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class EtocdnServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('etocdn', \Yan9\Etocdn\Middleware\EtocdnMiddleware::class);

        $this->publishes([
            __DIR__.'/Config/etocdn.php' => config_path('etocdn.php'),
        ], 'etocdn_config');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/Translations', 'etocdn');

        $this->publishes([
            __DIR__ . '/Translations' => resource_path('lang/vendor/etocdn'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/Views', 'etocdn');

        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/etocdn'),
        ]);

        $this->publishes([
            __DIR__ . '/Assets' => public_path('vendor/etocdn'),
        ], 'etocdn_assets');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Yan9\Etocdn\Commands\EtocdnCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/etocdn.php', 'etocdn'
        );
    }
}
