<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;
use Yajra\DataTables\Facades\DataTables;

class MasterItemController extends Controller
{
    public function index()
    {
        $master_items = MasterItem::all();
        return view('master_items.index', compact('master_items'));
    }

    public function getDropdownItem()
    {
        $master_items = MasterItem::select('id', 'name', 'item_code')->get();
    
        return response()->json($master_items);
    }

    public function getMasterItemList(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterItem::orderBy('id', 'DESC')->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('phone', function ($row) {
                    return $row->phone ?: '-';
                })
                ->addColumn('address', function ($row) {
                    return $row->address ?: '-';
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

    public function show($id)
    {
        $item = MasterItem::findOrFail($id);

        return response()->json([
            'item_code' => $item->item_code
        ]);
    }

    public function create()
    {
        return view('master_items.create');
    }

    public function store(Request $request)
    {
        MasterItem::create([
            'item_name'   => $request->item_name,
            'item_type'    => $request->item_type,            
            'description' => $request->description,
            'stock'   => $request->stock,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function edit(MasterItem $item)
    {
        return view('master_items.edit', compact('item'));
    }

    public function destroy(MasterItem $item)
    {
        $item->delete();

        return redirect()->route('master_items.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function updateMasterItem(Request $request, $id)
    {
        $data = MasterItem::findOrFail($id);
        $data->update([
            'item_name'    => $request->item_name,            
            'item_type'   => $request->item_type,
            'description' => $request->description,
            'stock'   => $request->stock,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteMasterItem(Request $request, $id)
    {
        $data = MasterItem::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
