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
        $this->loadPublishes();
        $this->registerCommands();
    }

    private function loadMigrations(): void
    {
        //$this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    private function loadPublishes(): void
    {
        $this->publishes([
            __DIR__.'/jobs/' => app_path('Jobs'),
        ],'JobsWa' );
        $this->publishes([
            __DIR__.'/models/' => app_path('Models'),
        ],'ModelsWa' );
         $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations'),
        ],'MigrationsWa' );
    }
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }

}