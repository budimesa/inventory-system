<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblematicItem extends Model
{
    use HasFactory;
    protected $fillable = ['asset_loan_id', 'master_item_id', 'status', 'return_date', 'notes', 'received_by'];
}
