<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeIdebanAdmin extends Command
{
    protected $signature = 'ideban:make-admin';
    protected $description = 'Create an administrator or grant administrator access to an existing user';

    public function handle()
    {
        $email = $this->ask('Administrator email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a valid email address.');

            return 1;
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            if (!$this->confirm('Grant administrator access to the existing account '.$user->email.'?')) {
                $this->info('No changes were made.');

                return 0;
            }
            $user->role = 'admin';
            $user->save();
            $this->info('Administrator access granted. The existing password was not changed.');

            return 0;
        }

        $name = $this->ask('Administrator name');
        if (!is_string($name) || trim($name) === '') {
            $this->error('Enter an administrator name.');

            return 1;
        }
        $password = $this->secret('Password (at least 12 characters)');
        if (strlen($password) < 12) {
            $this->error('The password must contain at least 12 characters.');

            return 1;
        }

        $confirmation = $this->secret('Confirm password');
        if (!hash_equals($password, $confirmation)) {
            $this->error('The password confirmation does not match.');

            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->role = 'admin';
        $user->save();
        $this->info('Administrator account created.');

        return 0;
    }
}
