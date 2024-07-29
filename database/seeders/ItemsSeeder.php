<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->insert([
            ['item_code' => 'BRG001', 'name' => 'Item X', 'description' => 'Description of Item X', 'stock' => '10', 'price' => '100000'],
            ['item_code' => 'BRG002', 'name' => 'Item Y', 'description' => 'Description of Item Y', 'stock' => '15', 'price' => '200000'],
            ['item_code' => 'BRG003', 'name' => 'Item Z', 'description' => 'Description of Item Z', 'stock' => '20', 'price' => '300000'],
        ]);
    }
}
