<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class IncomingItemController extends Controller
{
    public function index()
    {
        $incomingItems = IncomingItem::all();
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('incoming_items.index', compact('incomingItems', 'suppliers', 'items'));
    }

    public function getIncomingList(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomingItem::leftJoin('suppliers', 'incoming_items.supplier_id', '=', 'suppliers.id')
                                ->leftJoin('items', 'incoming_items.item_code', '=', 'items.item_code')
                                ->select('incoming_items.*', 'suppliers.name as supplier_name', 'items.name as item_name')
                                ->orderBy('id', 'DESC')
                                ->get();
            
            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('action', function ($row) {
                    $buttons = '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm btn-edit" data-toggle="modal" href="#Umodaldemo8" data-toggle="tooltip" data-original-title="Edit" data-edit=\''.json_encode($row).'\'><span class="fas fa-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-toggle="modal" href="#modalDemoDestroy" onclick=confirmDeleteItem(' . $row->id . ')><span class="fas fa-trash fs-14"></span></a>
                        </div>
                    ';
                    return $buttons;
                })
                ->addColumn('incoming_date', function ($row) {
                    return Carbon::parse($row->incoming_date)->format('Y-m-d');
                })    
                ->rawColumns(['action']) // Hanya action yang dinyatakan sebagai rawColumns
                ->make(true);
        }
    }

    public function create()
    {
        // Method to show the create form
    }

    public function store(Request $request)
    {
        IncomingItem::create([
            'incoming_code'   => $request->incoming_code,
            'item_code'   => $request->item_code,
            'supplier_id'    => $request->supplier_id,            
            'quantity'   => $request->quantity,
            'notes'   => $request->notes,
            'incoming_date' => $request->incoming_date,
        ]);

        // Find the item based on item_code
        $item = Item::where('item_code', $request->item_code)->first();

        // If item is found, update the stock
        if ($item) {
            $item->stock += $request->quantity;
            $item->save();
        }

        return response()->json(['success' => 'Berhasil']);
    }

    public function show($id)
    {
        // Method to show a specific incoming item (optional)
    }

    public function edit($id)
    {
        // Method to show the edit form (optional)
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'incoming_code' => 'required|string',
            'item_code' => 'required|string',
            'supplier_id' => 'required|string',
            'quantity' => 'required|integer',
            'incoming_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $incomingItem = IncomingItem::findOrFail($id);
        $incomingItem->update($request->all());

        return redirect()->route('incoming_items.index')
            ->with('success', 'Incoming item updated successfully.');
    }

    public function updateIncoming(Request $request, $id)
    {
        // Temukan incoming item yang akan diupdate
        $incomingItem = IncomingItem::findOrFail($id);

        // Simpan data incoming item yang lama untuk perhitungan stok
        $oldQuantity = $incomingItem->quantity;

        // Update incoming item dengan data baru
        $incomingItem->update([
            'incoming_code'   => $request->incoming_code,
            'item_code'       => $request->item_code,
            'supplier_id'     => $request->supplier_id,
            'quantity'        => $request->quantity,
            'notes'           => $request->notes,
            'incoming_date'   => $request->incoming_date,
        ]);

        // Hitung selisih quantity baru dan quantity lama untuk perubahan stok item
        $quantityDifference = $request->quantity - $oldQuantity;

        // Temukan item berdasarkan item_code
        $item = Item::where('item_code', $request->item_code)->first();

        // Jika item ditemukan, update stoknya sesuai perubahan quantity
        if ($item) {
            $item->stock += $quantityDifference;
            $item->save();
        }

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteIncoming(Request $request, $id)
    {
        $incomingItem = IncomingItem::findOrFail($id);
        $incomingItem->delete();

        // Kurangi stock di Item yang sesuai
        $item = Item::where('item_code', $incomingItem->item_code)->first();
        if ($item) {
            $item->stock -= $incomingItem->quantity;
            $item->save();
        }

        return response()->json(['message' => 'Data deleted successfully']);
    }

    public function destroy($id)
    {
        $incomingItem = IncomingItem::findOrFail($id);
        $incomingItem->delete();

        return redirect()->route('incoming_items.index')
            ->with('success', 'Incoming item deleted successfully.');
    }
}
