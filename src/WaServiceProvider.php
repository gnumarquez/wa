<?php

namespace Gnumarquez;

use Illuminate\Support\ServiceProvider;

class WaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();
        $this->loadModels();
    }

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    private function loadModels(): void
    {
        $this->loadModelsFrom(__DIR__ . '/models');
    }
}
