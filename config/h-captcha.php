<?php

declare(strict_types=1);

return [

    'site_key' => env('H_CAPTCHA_SITE_KEY'),

    'secret_key' => env('H_CAPTCHA_SECRET_KEY'),

    'url' => env('H_CAPTCHA_URL', 'https://hcaptcha.com/siteverify'),

];
