<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Employee([
            'employee_name' => $row['employee_name'],
            'division' => $row['division'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'nik' => $row['nik'],
        ]);
    }
}
