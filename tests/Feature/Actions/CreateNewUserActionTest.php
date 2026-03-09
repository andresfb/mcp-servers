<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use App\Actions\CreateNewUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('creates a user with valid input', function () {
    $action = new CreateNewUserAction;

    $user = $action->handle([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->name->toBe('John Doe')
        ->email->toBe('john@example.com');

    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('hashes the password', function () {
    $action = new CreateNewUserAction;

    $user = $action->handle([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    expect($user->password)->not->toBe('Password123!')
        ->and(Hash::check('Password123!', $user->password))
        ->toBeTrue();
});

it('fails validation when required fields are missing', function (array $input) {
    $action = new CreateNewUserAction;

    $action->handle($input);
})->throws(ValidationException::class)->with([
    'missing name' => [['email' => 'john@example.com', 'password' => 'Password123!', 'password_confirmation' => 'Password123!']],
    'missing email' => [['name' => 'John Doe', 'password' => 'Password123!', 'password_confirmation' => 'Password123!']],
    'missing password' => [['name' => 'John Doe', 'email' => 'john@example.com']],
]);

it('fails validation when email is not unique', function () {
    User::factory()->create(['email' => 'john@example.com']);

    $action = new CreateNewUserAction;

    $action->handle([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);
})->throws(ValidationException::class);

it('fails validation when password confirmation does not match', function () {
    $action = new CreateNewUserAction;

    $action->handle([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'DifferentPassword!',
    ]);
})->throws(ValidationException::class);
