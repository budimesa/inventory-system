<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    public function run()
    {
        DB::table('suppliers')->insert([
            ['name' => 'Supplier A', 'address' => 'Jakarta', 'email' => 'supplierA@example.com', 'phone' => '123456789'],
            ['name' => 'Supplier B', 'address' => 'Surabaya', 'email' => 'supplierB@example.com', 'phone' => '987654321'],
            ['name' => 'Supplier C', 'address' => 'Bandung', 'email' => 'supplierC@example.com', 'phone' => '456123789'],
        ]);
    }
}
