<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\ValidHCaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class LoginController extends Controller
{
    private string $cacheKey;

    public function __construct()
    {
        $this->cacheKey = sprintf(
            Config::string('constants.login_failed_key'),
            session()->id(),
        );
    }

    public function index(): View
    {
        return view(
            'auth.login',
            [
                'failed' => $this->hasFailed(),
                'site_key' => Config::string('h-captcha.site_key'),
            ],
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validate($request);

        if (! Auth::attempt($validated)) {
            Cache::put($this->cacheKey, 1, now()->addHour());

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        Cache::delete($this->cacheKey);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    private function hasFailed(): bool
    {
        return Cache::has($this->cacheKey);
    }

    private function validate(Request $request): array
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];

        if ($this->hasFailed()) {
            $rules['h-captcha-response'] = ['required', new ValidHCaptcha()];
        }

        return $request->validate($rules);
    }
}
