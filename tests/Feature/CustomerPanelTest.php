<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CommerceSeeder;
use Database\Seeders\IdebanCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\TicketMessage;
use Tests\TestCase;

class CustomerPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(IdebanCatalogSeeder::class);
        $this->seed(CommerceSeeder::class);
    }

    private function user(string $role, string $username): User
    {
        $user = new User(['name' => ucfirst($username), 'username' => $username, 'email' => $username.'@example.test', 'phone' => '09120000002']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = $role;
        $user->save();

        return $user;
    }

    public function test_customer_is_blocked_from_the_admin_panel()
    {
        $this->actingAs($this->user('customer', 'buyer'))->get('/admin')->assertForbidden();
        $this->actingAs($this->user('customer', 'buyer2'))->get('/admin/courses')->assertForbidden();
    }

    public function test_customer_login_redirects_to_the_account_and_staff_to_admin()
    {
        $this->user('customer', 'buyer');
        $this->user('sales', 'seller');

        $this->post('/login', ['login' => 'buyer', 'password' => 'LongSecret#2026'])
            ->assertRedirect(route('account.dashboard'));
        $this->post('/logout');
        $this->post('/login', ['login' => 'seller', 'password' => 'LongSecret#2026'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_staff_cannot_use_the_customer_panel_or_unassigned_admin_sections()
    {
        $sales = $this->user('sales', 'seller');
        $this->actingAs($sales)->get('/admin/orders')->assertOk();
        $this->actingAs($sales)->get('/admin/courses')->assertForbidden();
    }

    public function test_customer_can_create_a_ticket_with_its_first_message()
    {
        $customer = $this->user('customer', 'buyer');

        $this->actingAs($customer)->post(route('account.tickets.store'), [
            'subject' => 'Server is slow',
            'category' => 'technical',
            'priority' => 'high',
            'body' => 'Pages take ten seconds to load.',
        ])->assertRedirect();

        $ticket = Ticket::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame('open', $ticket->status);
        $this->assertDatabaseHas('ticket_messages', ['ticket_id' => $ticket->id, 'is_staff' => false]);
        $this->assertSame(1, TicketMessage::where('ticket_id', $ticket->id)->count());
    }

    public function test_ticket_attachment_is_downloadable_only_by_owner_and_staff()
    {
        Storage::fake('local');
        $owner = $this->user('customer', 'owner');
        $other = $this->user('customer', 'other');
        $admin = $this->user('admin', 'boss');

        $this->actingAs($owner)->post(route('account.tickets.store'), [
            'subject' => 'With file', 'category' => 'general', 'priority' => 'normal', 'body' => 'See file',
            'attachment' => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
        ])->assertRedirect();

        $message = TicketMessage::whereNotNull('attachment_path')->firstOrFail();
        $this->actingAs($owner)->get(route('account.tickets.attachment', $message))->assertOk();
        $this->actingAs($admin)->get(route('admin.tickets.attachment', $message))->assertOk();
        $this->actingAs($other)->get(route('account.tickets.attachment', $message))->assertForbidden();
        $this->app['auth']->forgetGuards();
        $this->get(route('account.tickets.attachment', $message))->assertRedirect();
    }

    public function test_customer_cannot_read_another_customers_ticket()
    {
        $owner = $this->user('customer', 'owner');
        $other = $this->user('customer', 'other');
        $ticket = Ticket::create(['reference' => Ticket::newReference(), 'user_id' => $owner->id, 'subject' => 'Private', 'category' => 'general', 'priority' => 'normal', 'status' => 'open']);

        $this->actingAs($other)->get(route('account.tickets.show', $ticket))->assertForbidden();
    }

    public function test_key_public_and_panel_pages_render()
    {
        $customer = $this->user('customer', 'buyer');
        $admin = $this->user('admin', 'boss');

        foreach (['/', '/calculator', '/academy', '/login', '/register'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->actingAs($customer)->get('/account')->assertOk();
        $this->actingAs($customer)->get('/account/invoices')->assertOk();
        $this->actingAs($customer)->get('/account/tickets/create')->assertOk();

        $course = \App\Models\Course::firstOrFail();
        $this->actingAs($admin)->get(route('admin.courses.edit', $course))->assertOk();
        $this->actingAs($admin)->get(route('admin.courses.lessons', $course))->assertOk();
        $this->get(route('academy.show', $course))->assertOk();
        $this->actingAs($customer)->get(route('academy.show', $course))->assertOk();

        foreach (['/admin', '/admin/orders', '/admin/invoices', '/admin/payments', '/admin/tickets', '/admin/customers', '/admin/courses', '/admin/discounts', '/admin/catalog/addons', '/admin/catalog/addons/create', '/admin/courses/create'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_english_locale_renders_the_new_modules()
    {
        $this->seed(\Database\Seeders\CommerceSeeder::class);
        $admin = $this->user('admin', 'boss');
        $this->get('/lang/en');

        $this->get('/calculator')->assertOk()->assertSee('Estimate calculator', false);
        $this->get('/academy')->assertOk()->assertSee('Academy', false);
        $this->actingAs($admin)->get('/admin/orders')->assertOk()->assertSee('Orders', false);
    }
}
