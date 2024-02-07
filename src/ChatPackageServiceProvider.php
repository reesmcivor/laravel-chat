<?php

namespace ReesMcIvor\Chat;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ChatPackageServiceProvider extends ServiceProvider
{

    protected $namespace = 'ReesMcIvor\Chat\Http\Controllers';

    public function boot()
    {

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('chat:conversation:auto_close')->everyMinute();
        });

        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/tenant' => database_path('migrations'),
                //__DIR__ . '/../database/factories' => database_path('factories'),
                __DIR__ . '/../publish/tests' => base_path('tests/Chat'),
            ], 'reesmcivor-chat');
        }

        $this->loadRoutesFrom(__DIR__.'/routes/tenant.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'chat');
        $this->mergeConfigFrom(__DIR__.'/../config/chat.php', 'chat');

        $this->commands([
            \ReesMcIvor\Chat\Console\Commands\Conversations\AutoClose::class,
        ]);
    }

    protected function mapBroadcastRoutes()
    {
        Route::prefix('broadcasting')
            ->middleware(['web', 'auth'])
            ->namespace($this->namespace)
            ->group(module_path('Chat', 'routes/broadcast.php'));
    }


    private function modulePath($path)
    {
        return __DIR__ . '/../../' . $path;
    }
}
