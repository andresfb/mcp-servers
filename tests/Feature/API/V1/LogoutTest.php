<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('logs out an authenticated user and deletes the token', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson(route('logout'))
        ->assertSuccessful()
        ->assertJson(['message' => 'Logged out successfully.']);

    expect($user->tokens()->count())->toBe(0);
});

it('returns unauthenticated when no token is provided', function () {
    $this->postJson(route('logout'))
        ->assertUnauthorized();
});
