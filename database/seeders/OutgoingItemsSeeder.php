<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutgoingItemsSeeder extends Seeder
{
    public function run()
    {
        $itemCodes = DB::table('items')->pluck('item_code');

        // Insert dummy data
        DB::table('outgoing_items')->insert([
            [
                'outgoing_code' => 'OUT0001',
                'item_code' => $itemCodes->random(),
                'quantity' => 20,
                'outgoing_date' => now()->subDays(4)->format('Y-m-d'),
                'destination' => 'Gudang A',
                'notes' => 'Dipindahkan ke Gudang A',
            ],
            [
                'outgoing_code' => 'OUT0002',
                'item_code' => $itemCodes->random(),
                'quantity' => 15,
                'outgoing_date' => now()->subDays(2)->format('Y-m-d'),
                'destination' => 'Gudang B',
                'notes' => 'Dipindahkan ke Gudang B',
            ],
            [
                'outgoing_code' => 'OUT0003',
                'item_code' => $itemCodes->random(),
                'quantity' => 25,
                'outgoing_date' => now()->subDays(1)->format('Y-m-d'),
                'destination' => 'Gudang C',
                'notes' => 'Dipindahkan ke Gudang C',
            ],
        ]);
    }
}
