<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $fillable = ['outgoing_code', 'item_code', 'quantity', 'outgoing_date', 'destination', 'notes'];

    protected $casts = [
        'outgoing_date' => 'date',
    ];

}

