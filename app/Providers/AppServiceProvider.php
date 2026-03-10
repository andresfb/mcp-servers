<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureVite();

        if ($this->app->isLocal() || Config::boolean('app.internal')) {
            URL::forceScheme('http');
        } else {
            $this->app['request']->server->set('HTTPS', 'on');
            URL::forceScheme('https');
        }

        RateLimiter::for('login', static fn (Request $request): array => [
            Limit::perMinute(25),
            Limit::perMinute(5)->by($request->input('email')),
        ]);
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
    }

    /**
     * Configure the application's Vite instance.
     */
    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }
}
