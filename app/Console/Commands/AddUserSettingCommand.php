<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserSettigns;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\form;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\warning;

final class AddUserSettingCommand extends Command
{
    protected $signature = 'add:setting';

    protected $description = 'Add a User Setting';

    public function handle(): void
    {
        try {
            clear();
            intro('Add User Setting');

            $user = $this->login();

            $results = form()
                ->text(
                    label: 'Key Name',
                    default: Config::string('constants.prompter_api_key_name'),
                    required: true,
                    validate: 'string|max:255',
                    name: 'key',
                )
                ->textarea(
                    label: 'Value',
                    required: true,
                    rows: 10,
                    name: 'value',
                )
                ->submit();

            $this->line('Saving User Setting');

            UserSettigns::updateOrCreate([
                'user_id' => $user->id,
                'key' => $results['key'],
            ], [
                'value' => $results['value'],
            ]);
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }

    private function login(): User
    {
        info('Logging in');

        $response = form()
            ->text(
                label: 'Email',
                default: Config::string('constants.admin_email'),
                required: true,
                validate: 'string|email|max:255',
                name: 'email',
            )
            ->password(
                label: 'Password',
                required: true,
                validate: 'string|min:8|max:255',
                name: 'password',
            )
            ->submit();

        $user = User::query()
            ->where('email', $response['email'])
            ->firstOrFail();

        if (! Hash::check($response['password'], $user->password)) {
            throw new RuntimeException('Invalid credentials');
        }

        warning('Logged In');

        return $user;
    }
}
