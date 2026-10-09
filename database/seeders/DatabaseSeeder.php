<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IdebanCatalogSeeder::class);
        $this->call(ContentSeeder::class);
        $this->call(DemoContentSeeder::class);
        $this->call(CommerceSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(AdminAccountSeeder::class);
    }
}
