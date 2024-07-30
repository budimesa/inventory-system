<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $divisions = ['ACCOUNTING', 'ADM', 'AUDIT', 'DEPO SUPPORT', 'DIREKSI', 'DRIVER', 
        'EXIM','FINANCE','HRD','IT','MARKETING', 'MESSENGER', 'MPD', 'NPD', 'OB',
        'PAJAK','PURCHASING','SEKRETARIS'];
        return view('employees.index', compact('employees', 'divisions'));
    }

    public function getDropdownEmployee()
    {
        $employees = Employee::select('id', 'employee_name')->get();
        return response()->json($employees);
    }

    public function getEmployeeList(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::orderBy('id', 'DESC')->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('division', function ($row) {
                    return $row->division ?: '-';
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone ?: '-';
                })
                ->addColumn('action', function ($row) {                   
                    $buttons = '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm btn-edit" data-toggle="modal" href="#Umodaldemo8" data-toggle="tooltip" data-original-title="Edit" data-edit=\''.json_encode($row).'\'><span class="fas fa-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-toggle="modal" href="#modalDemoDestroy" onclick=confirmDeleteItem(' . $row->id . ')><span class="fas fa-trash fs-14"></span></a>
                        </div>
                    ';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        Employee::create([
            'employee_name' => $request->employee_name,
            'division'      => $request->division,
            'phone'         => $request->phone,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function updateEmployee(Request $request, $id)
    {
        $data = Employee::findOrFail($id);
        $data->update([
            'employee_name' => $request->employee_name,
            'division'      => $request->division,
            'phone'         => $request->phone,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteEmployee(Request $request, $id)
    {
        $data = Employee::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json(['message' => 'Employee berhasil dihapus.']);
    }    
}