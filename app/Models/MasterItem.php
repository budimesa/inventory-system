<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_type','item_name', 'description', 'stock'];

    public function assetLoans()
    {
        return $this->belongsToMany(AssetLoan::class, 'asset_loan_master_item')
                    ->withPivot('quantity');
    }

    public function problematicItems()
    {
        return $this->hasMany(ProblematicItem::class, 'master_item_id');
    }

    public function scopeWithCounts($query)
    {
        $query->withCount([
            'assetLoans as borrowed_count' => function ($query) {
                $query->whereNull('return_date');
            },
            'problematicItems as problematic_count' => function ($query) {
                $query->whereNull('return_date');
            }
        ])
        ->selectRaw('master_items.*, 
                    (stock + 
                    (SELECT COUNT(*) 
                    FROM asset_loan_master_item 
                    INNER JOIN asset_loans 
                    ON asset_loan_master_item.asset_loan_id = asset_loans.id 
                    WHERE asset_loan_master_item.master_item_id = master_items.id 
                    AND asset_loans.return_date IS NULL) + 
                    (SELECT COUNT(*) 
                    FROM problematic_items 
                    WHERE problematic_items.master_item_id = master_items.id 
                    AND problematic_items.return_date IS NULL)) as total_stock');
    }


    
}
