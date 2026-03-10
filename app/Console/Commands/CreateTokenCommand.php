<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Traits\UserLogin;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

final class CreateTokenCommand extends Command
{
    use UserLogin;

    protected $signature = 'create:token';

    protected $description = 'Creates a new API token for a given user';

    public function handle(): void
    {
        try {
            clear();
            intro('Create a API token');

            $user = $this->login();

            $name = text(
                label: 'Client Name',
                default: 'CLAUDE',
                required: true,
                validate: 'string|min:3|max:10',
            );

            $token = $user->createToken(
                str($name)->upper()
                    ->trim()
                    ->toString()
            );

            $this->newLine();
            warning($token->plainTextToken);
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
