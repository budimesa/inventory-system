<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeImportController extends Controller
{
    public function showImportForm()
    {
        return view('employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        
        Excel::import(new EmployeesImport, $request->file('file'));

        return redirect()->back()->with('success', 'Employees imported successfully.');
    }
}
