<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function getDropdownSupplier()
    {
        $suppliers = Supplier::select('id', 'name')->get();
    
        return response()->json($suppliers);
    }

    public function getSupplierList(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::orderBy('id', 'DESC')->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('email', function ($row) {
                    return $row->email ?: '-';
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone ?: '-';
                })
                ->addColumn('address', function ($row) {
                    return $row->address ?: '-';
                })
                ->addColumn('contact_person', function ($row) {
                    return $row->contact_person ?: '-';
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
                ->rawColumns(['action']) // Hanya action yang dinyatakan sebagai rawColumns
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        Supplier::create([
            'name'    => $request->name,            
            'phone'   => $request->phone,
            'email'   => $request->email,
            'contact_person'   => $request->contact_person,
            'address' => $request->address,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function updateSupplier(Request $request, $id)
    {
        $data = Supplier::findOrFail($id);
        $data->update([
            'name' => $request->name,            
            'phone'   => $request->phone,
            'address'   => $request->address,
            'contact_person' => $request->contact_person,            
            'email'   => $request->email,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteSupplier(Request $request, $id)
    {
        $data = Supplier::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }


    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json(['message' => 'Supplier berhasil dihapus.']);
    }    

    
}
