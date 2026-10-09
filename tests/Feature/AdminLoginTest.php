<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = new User(['name' => 'Admin', 'email' => 'admin@ideban.local', 'username' => 'admin']);
        $user->password = Hash::make('LongSecret#2026');
        $user->role = 'admin';
        $user->save();

        return $user;
    }

    public function test_admin_can_log_in_with_username()
    {
        $this->admin();

        $this->post('/login', ['login' => 'admin', 'password' => 'LongSecret#2026'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_log_in_with_email()
    {
        $this->admin();

        $this->post('/login', ['login' => 'admin@ideban.local', 'password' => 'LongSecret#2026'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_wrong_password_is_rejected()
    {
        $this->admin();

        $this->post('/login', ['login' => 'admin', 'password' => 'wrong'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
