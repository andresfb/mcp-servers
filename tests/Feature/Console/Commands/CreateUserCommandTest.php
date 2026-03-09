<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a new user', function () {
    $this->artisan('create:user')
        ->expectsQuestion('Name', 'John Doe')
        ->expectsQuestion('Email', 'john@example.com')
        ->expectsQuestion('Password', 'Password123!')
        ->expectsQuestion('Confirm Password', 'Password123!')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect(User::where('email', 'john@example.com')->first())
        ->email_verified_at->not->toBeNull();
});

it('marks the user email as verified', function () {
    $this->artisan('create:user')
        ->expectsQuestion('Name', 'Jane Doe')
        ->expectsQuestion('Email', 'jane@example.com')
        ->expectsQuestion('Password', 'Password123!')
        ->expectsQuestion('Confirm Password', 'Password123!')
        ->assertSuccessful();

    $user = User::where('email', 'jane@example.com')->first();

    expect($user->email_verified_at)->not->toBeNull();
});

it('shows error when email already exists', function () {
    User::factory()->create(['email' => 'john@example.com']);

    $this->artisan('create:user')
        ->expectsQuestion('Name', 'John Doe')
        ->expectsQuestion('Email', 'john@example.com')
        ->expectsQuestion('Password', 'Password123!')
        ->expectsQuestion('Confirm Password', 'Password123!')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    expect(User::where('email', 'john@example.com')->count())->toBe(1);
});

it('shows error when passwords do not match', function () {
    $this->artisan('create:user')
        ->expectsQuestion('Name', 'John Doe')
        ->expectsQuestion('Email', 'john@example.com')
        ->expectsQuestion('Password', 'Password123!')
        ->expectsQuestion('Confirm Password', 'DifferentPassword!')
        ->expectsPromptsOutro('Done')
        ->assertSuccessful();

    $this->assertDatabaseMissing('users', [
        'email' => 'john@example.com',
    ]);
});
