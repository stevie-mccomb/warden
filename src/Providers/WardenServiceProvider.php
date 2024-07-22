<?php

namespace Stevie\Warden\Providers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Stevie\Warden\Console\Commands\UpdateCommand;

class WardenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/warden.php', 'warden');

        if (config('app.env') === 'workbench') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->defineGates();
        $this->definePublishableAssets();
        $this->registerConsoleCommands();
    }

    /**
     * Define the assets that can be published to the parent project.
     */
    private function definePublishableAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../config/warden.php' => config_path('warden.php'),
        ], [ 'warden', 'warden-config' ]);

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], [ 'warden', 'warden-migrations' ]);
    }

    /**
     * Define the authorization gates that can be used to enforce Warden-based security.
     */
    private function defineGates(): void
    {
        Gate::define('warden', [ config('warden.class_map.gates.can'), 'can' ]);
        Gate::before(function (Authenticatable $user, string $capability, mixed $resource = null): ?bool {
            return (new (config('warden.class_map.gates.before')))->before($user, $capability, $resource);
        });
    }

    /**
     * Register the console commands that Warden provides to Artisan.
     */
    private function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateCommand::class,
            ]);
        }
    }
}
