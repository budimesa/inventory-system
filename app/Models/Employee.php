<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_name', 'division', 'phone', 'email', 'nik'];
    
    public static function getDivisions()
    {
        return [
            'admin_marketing_pusat' => 'Admin Marketing Pusat',
            'finance_accounting_pusat' => 'Finance & Accounting Pusat',
            'purchasing' => 'Purchasing',
            'hrd' => 'HRD',
            'exim' => 'Exim',
            'tax' => 'Tax',
            'umum_jakarta' => 'Umum Jakarta',
            'sales_marketing_pusat' => 'Sales & Marketing Pusat',
            'sekretaris' => 'Sekretaris',
            'mpd_dg' => 'Mpd / D. G.',
            'depo_support' => 'Depo Support',
            'mis' => 'MIS',
            'auditor' => 'Auditor',
        ];
    }
}
