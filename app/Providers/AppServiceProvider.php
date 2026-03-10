<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Override;
use Symfony\Component\HttpFoundation\Response;

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

        if ($this->app->isProduction() || Config::boolean('app.internal')) {
            $this->app['request']->server->set('HTTPS', 'on');
            URL::forceScheme('https');
        } else {
            URL::forceScheme('http');
        }

        RateLimiter::for('login', static fn (Request $request): array => [
            Limit::perMinute(25),
            Limit::perMinute(5)->by($request->input('email')),
        ]);

        Passport::authorizationView(static function (array $parameters): Response {
            return response(view('mcp.authorize', $parameters)->render());
        });

        Passport::tokensExpireIn(CarbonInterval::days(60));
        Passport::refreshTokensExpireIn(CarbonInterval::days(90));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
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
