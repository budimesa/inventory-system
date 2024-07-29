<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function getDropdownItem()
    {
        $items = Item::select('id', 'name', 'item_code')->get();
    
        return response()->json($items);
    }

    public function getItemList(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::orderBy('id', 'DESC')->get();
            
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
        $item = Item::findOrFail($id);

        return response()->json([
            'item_code' => $item->item_code
        ]);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        Item::create([
            'item_code'   => $request->item_code,
            'name'    => $request->name,            
            'stock'   => 0,
            'price'   => $request->price,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function updateItem(Request $request, $id)
    {
        $data = Item::findOrFail($id);
        $data->update([
            'item_code'   => $request->item_code,
            'name'    => $request->name,            
            'price'   => $request->price,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteItem(Request $request, $id)
    {
        $data = Item::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
