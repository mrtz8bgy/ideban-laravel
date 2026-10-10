<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\User;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function admin(): User
    {
        $user = new User(['name' => 'Admin', 'username' => 'menuadmin', 'email' => 'menuadmin@example.test', 'phone' => '09120000007']);
        $user->password = bcrypt('LongSecret#2026');
        $user->role = 'admin';
        $user->save();

        return $user;
    }

    private function makeTree(): MenuItem
    {
        $top = MenuItem::create(['location' => 'header', 'label_fa' => 'خدمات', 'label_en' => 'Services', 'url' => '/services', 'sort_order' => 1, 'is_active' => true]);
        $column = MenuItem::create(['location' => 'header', 'parent_id' => $top->id, 'label_fa' => 'شبکه', 'label_en' => 'Network Column', 'url' => '/services#network', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['location' => 'header', 'parent_id' => $column->id, 'label_fa' => 'فایبر', 'label_en' => 'Fiber Link Item', 'url' => '/services/fiber', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['location' => 'header', 'label_fa' => 'مخفی', 'label_en' => 'Hidden Menu Item', 'url' => '/x', 'sort_order' => 2, 'is_active' => false]);

        return $top;
    }

    public function test_mega_menu_renders_children_on_public_pages()
    {
        $this->makeTree();
        $this->get('/lang/en');

        $this->get('/')->assertOk()
            ->assertSee('class="mega"', false)
            ->assertSee('Network Column')
            ->assertSee('Fiber Link Item')
            ->assertDontSee('Hidden Menu Item');
    }

    public function test_tree_orders_children_under_parents()
    {
        $top = $this->makeTree();
        $tree = MenuItem::tree('header');

        $this->assertCount(1, $tree);
        $this->assertEquals($top->id, $tree->first()->id);
        $this->assertCount(1, $tree->first()->children);
        $this->assertCount(1, $tree->first()->children->first()->children);
    }

    public function test_admin_can_add_nested_item_up_to_three_levels()
    {
        $this->admin();
        $top = $this->makeTree();
        $column = MenuItem::where('label_en', 'Network Column')->first();

        $this->actingAs(User::where('username', 'menuadmin')->first())
            ->post(route('admin.menu.store'), [
                'location' => 'header', 'parent_id' => $column->id, 'label_fa' => 'سطح سه', 'label_en' => 'Level Three',
                'url' => '/services/x', 'sort_order' => 5, 'is_active' => 1,
            ])->assertRedirect(route('admin.menu.index'));
        $this->assertDatabaseHas('menu_items', ['label_en' => 'Level Three', 'parent_id' => $column->id]);
    }

    public function test_fourth_level_and_self_parent_are_rejected()
    {
        $this->admin();
        $this->makeTree();
        $fiber = MenuItem::where('label_en', 'Fiber Link Item')->first();
        $top = MenuItem::where('label_en', 'Services')->first();

        $this->actingAs(User::where('username', 'menuadmin')->first())
            ->post(route('admin.menu.store'), [
                'location' => 'header', 'parent_id' => $fiber->id, 'label_fa' => 'چهار', 'label_en' => 'Too Deep',
                'url' => '/x', 'is_active' => 1,
            ])->assertSessionHasErrors('parent_id');
        $this->assertDatabaseMissing('menu_items', ['label_en' => 'Too Deep']);

        $this->actingAs(User::where('username', 'menuadmin')->first())
            ->put(route('admin.menu.update', $top), [
                'location' => 'header', 'parent_id' => $top->id, 'label_fa' => 'خدمات', 'label_en' => 'Services',
                'url' => '/services', 'is_active' => 1,
            ])->assertSessionHasErrors('parent_id');
    }

    public function test_guest_cannot_manage_menu()
    {
        $this->get(route('admin.menu.index'))->assertRedirect();
    }

    public function test_menu_links_follow_the_site_folder()
    {
        \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost/ideban-laravel/public');
        $this->assertSame('http://localhost/ideban-laravel/public/services/business-website',
            \App\Support\AppLink::resolve('/services/business-website'));
        $this->assertSame('http://localhost/ideban-laravel/public/services#network',
            \App\Support\AppLink::resolve('/services#network'));
        $this->assertSame('http://localhost/ideban-laravel/public/pricing?x=1#plans',
            \App\Support\AppLink::resolve('/pricing?x=1#plans'));
        $this->assertSame('#top', \App\Support\AppLink::resolve('#top'));
        $this->assertSame('https://example.com/a', \App\Support\AppLink::resolve('https://example.com/a'));

        \Illuminate\Support\Facades\URL::forceRootUrl(null);
        $this->assertSame('http://localhost/services/x', \App\Support\AppLink::resolve('/services/x'));

        $this->makeTree();
        $this->get('/lang/en');
        $this->get('/')->assertOk()->assertSee('href="http://localhost/services#network"', false);
    }
}
