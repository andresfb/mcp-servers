<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds a setting for an authenticated user', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    $this->artisan('add:setting')
        ->expectsQuestion('Email', $user->email)
        ->expectsQuestion('Password', 'Password123!')
        ->expectsQuestion('Key Name', 'api_key')
        ->expectsQuestion('Value', 'some-secret-value')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    $this->assertDatabaseHas('user_settigns', [
        'user_id' => $user->id,
        'key' => 'api_key',
    ]);

    $setting = UserSettings::where('user_id', $user->id)->where('key', 'api_key')->first();
    expect($setting->value)->toBe('some-secret-value');
});

it('updates an existing setting with the same key', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    UserSettings::create([
        'user_id' => $user->id,
        'key' => 'api_key',
        'value' => 'old-value',
    ]);

    $this->artisan('add:setting')
        ->expectsQuestion('Email', $user->email)
        ->expectsQuestion('Password', 'Password123!')
        ->expectsQuestion('Key Name', 'api_key')
        ->expectsQuestion('Value', 'new-value')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    expect(UserSettings::where('user_id', $user->id)->where('key', 'api_key')->count())->toBe(1);

    $setting = UserSettings::where('user_id', $user->id)->where('key', 'api_key')->first();
    expect($setting->value)->toBe('new-value');
});

it('shows error when user does not exist', function () {
    $this->artisan('add:setting')
        ->expectsQuestion('Email', 'nonexistent@example.com')
        ->expectsQuestion('Password', 'Password123!')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    $this->assertDatabaseCount('user_settigns', 0);
});

it('shows error when password is invalid', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    $this->artisan('add:setting')
        ->expectsQuestion('Email', $user->email)
        ->expectsQuestion('Password', 'WrongPassword!')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    $this->assertDatabaseCount('user_settigns', 0);
});
