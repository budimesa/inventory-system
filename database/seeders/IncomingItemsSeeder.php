<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomingItemsSeeder extends Seeder
{
    public function run()
    {
        $supplierIds = DB::table('suppliers')->pluck('id');
        $itemCodes = DB::table('items')->pluck('item_code');

        // Insert dummy data
        DB::table('incoming_items')->insert([
            [   'incoming_code' => 'INC0001',
                'item_code' => $itemCodes->random(),
                'supplier_id' => $supplierIds->random(),
                'quantity' => 50,
                'incoming_date' => now()->subDays(5)->format('Y-m-d'),
                'notes' => 'Received from Supplier',
            ],
            [   'incoming_code' => 'INC0002',
                'item_code' => $itemCodes->random(),
                'supplier_id' => $supplierIds->random(),
                'quantity' => 30,
                'incoming_date' => now()->subDays(3)->format('Y-m-d'),
                'notes' => 'Received from Supplier',
            ],
            [   'incoming_code' => 'INC0003',
                'item_code' => $itemCodes->random(),
                'supplier_id' => $supplierIds->random(),
                'quantity' => 40,
                'incoming_date' => now()->subDays(1)->format('Y-m-d'),
                'notes' => 'Received from Supplier',
            ],
        ]);
    }
}
