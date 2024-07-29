<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Budi',
            'email' => 'budihmesa19@gmail.com',
            'password' => bcrypt('Asdf1234'),
        ]);

        // $this->call(SuppliersSeeder::class);
        // $this->call(ItemsSeeder::class);
        // $this->call(IncomingItemsSeeder::class);
        // $this->call(OutgoingItemsSeeder::class);
    }
}
