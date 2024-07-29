<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    use HasFactory;

    protected $fillable = ['incoming_code', 'item_code', 'supplier_id', 'item_id', 'quantity', 'incoming_date', 'notes'];

    protected $casts = [
        'incoming_date' => 'date',
    ];
}

