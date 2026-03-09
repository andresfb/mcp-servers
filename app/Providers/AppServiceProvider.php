<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonInterval;
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
        Passport::authorizationView(static function (array $parameters): Response {
            return response(view('mcp.authorize', $parameters)->render());
        });

        Passport::tokensExpireIn(CarbonInterval::days(60));
        Passport::refreshTokensExpireIn(CarbonInterval::days(90));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
    }
}
