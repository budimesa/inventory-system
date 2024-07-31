<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLoan extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'item_id', 'borrow_date', 'item_id', 'planned_return_date', 'loan_reason', 'notes'];

    public function masterItems()
    {
        return $this->belongsToMany(MasterItem::class, 'asset_loan_master_item');
    }
}
