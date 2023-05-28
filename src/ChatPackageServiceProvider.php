<?php

namespace ReesMcIvor\Chat;

use Illuminate\Support\ServiceProvider;

class ChatPackageServiceProvider extends ServiceProvider
{

    protected $namespace = 'ReesMcIvor\Chat\Http\Controllers';

    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/tenant' => database_path('migrations/tenant'),
                //__DIR__ . '/../database/factories' => database_path('factories'),
                __DIR__ . '/../publish/tests' => base_path('tests/Chat'),
            ], 'reesmcivor-chat');
        }

        $this->loadRoutesFrom(__DIR__.'/routes/tenant.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'forms');
    }

    public function map()
    {
        $this->mapTenantRoutes();
    }

    protected function mapTenantRoutes()
    {
        Route::middleware(['web', 'tenant'])
            ->namespace($this->namespace)
            ->group($this->modulePath('routes/tenant.php'));
    }

    private function modulePath($path)
    {
        return __DIR__ . '/../../' . $path;
    }
}
