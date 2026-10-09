<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Creates the first administrator with username "admin".
 * The password comes from IDEBAN_ADMIN_PASSWORD; if unset, a random one is generated and printed once.
 * Change it after the first login.
 */
class AdminAccountSeeder extends Seeder
{
    public function run()
    {
        if (User::where('username', 'admin')->exists()) {
            return;
        }

        $fromEnv = env('IDEBAN_ADMIN_PASSWORD');
        $password = $fromEnv ?: Str::random(20);

        $user = new User(['name' => 'Ideban Admin', 'email' => 'admin@ideban.local', 'username' => 'admin']);
        $user->password = Hash::make($password);
        $user->role = 'admin';
        $user->email_verified_at = now();
        $user->save();

        if (!$fromEnv && $this->command) {
            $this->command->warn('Generated admin password (username: admin): '.$password);
        }
    }
}
