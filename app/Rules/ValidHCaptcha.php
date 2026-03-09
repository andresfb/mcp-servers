<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class ValidHCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = Http::asForm()->post(Config::string('h-captcha.url'), [
                'secret' => Config::string('h-captcha.secret_key'),
                'response' => $value,
            ]);

            if (! $response->json('success')) {
                $fail('Invalid CAPTCHA. You need to prove you are human');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $fail($e->getMessage());
        }
    }
}
