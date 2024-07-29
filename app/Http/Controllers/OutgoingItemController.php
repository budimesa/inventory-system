<?php

namespace App\Http\Controllers;

use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Item;
use Yajra\DataTables\Facades\DataTables;

class OutgoingItemController extends Controller
{
    public function index()
    {
        $outgoingItems = OutgoingItem::all();
        $items = Item::all();
        return view('outgoing_items.index', compact('outgoingItems', 'items'));
    }

    public function getOutgoingList(Request $request)
    {
        if ($request->ajax()) {
            $data = OutgoingItem::orderBy('id', 'DESC')
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
                ->addColumn('outgoing_date', function ($row) {
                    return Carbon::parse($row->outgoing_date)->format('Y-m-d');
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

        // Temukan item berdasarkan item_code
        $item = Item::where('item_code', $request->item_code)->first();

        // Jika item ditemukan
        if ($item) {
            // Cek apakah stok mencukupi
            if ($item->stock < $request->quantity) {
                return response()->json(['error' => 'Stok tidak mencukupi untuk item ini.'], 400);
            }

            // Kurangi stok item
            $item->stock -= $request->quantity;
            $item->save();
        } else {
            return response()->json(['error' => 'Item tidak ditemukan.'], 404);
        }

        OutgoingItem::create([
            'outgoing_code'   => $request->outgoing_code,
            'outgoing_date' => $request->outgoing_date,
            'item_code'   => $request->item_code,                   
            'quantity'   => $request->quantity,
            'destination'   => $request->destination,
            'notes'   => $request->notes,            
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function show($id)
    {
        // Method to show a specific outgoing item (optional)
    }

    public function edit($id)
    {
        // Method to show the edit form (optional)
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'outgoing_code' => 'required|string',
            'item_code' => 'required|string',
            'quantity' => 'required|integer',
            'outgoing_date' => 'required|date',
            'destination' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $outgoingItem = OutgoingItem::findOrFail($id);
        $outgoingItem->update($request->all());

        return redirect()->route('outgoing_items.index')
            ->with('success', 'Outgoing item updated successfully.');
    }

    public function destroy($id)
    {
        $outgoingItem = OutgoingItem::findOrFail($id);
        $outgoingItem->delete();

        return redirect()->route('outgoing_items.index')
            ->with('success', 'Outgoing item deleted successfully.');
    }

    public function updateOutgoing(Request $request, $id)
    {
        // Temukan outgoing item yang akan diupdate
        $outgoingItem = OutgoingItem::findOrFail($id);

        // Simpan data outgoing item yang lama untuk perhitungan stok
        $oldQuantity = $outgoingItem->quantity;

        // Update outgoing item dengan data baru
        $outgoingItem->update([
            'outgoing_code'   => $request->outgoing_code,
            'outgoing_date'   => $request->outgoing_date,
            'item_code'       => $request->item_code,            
            'quantity'        => $request->quantity,
            'destination'     => $request->destination,
            'notes'           => $request->notes,
            
        ]);

        // Hitung selisih quantity baru dan quantity lama untuk perubahan stok item
        $quantityDifference = $oldQuantity - $request->quantity;

        // Temukan item berdasarkan item_code
        $item = Item::where('item_code', $request->item_code)->first();

        // Jika item ditemukan, update stoknya sesuai perubahan quantity
        if ($item) {
            // Logika untuk penyesuaian stok berdasarkan perbedaan quantity
            if ($oldQuantity > $request->quantity) {
                // Jika oldQuantity lebih besar dari quantity baru, tambahkan stok
                $item->stock += abs($quantityDifference);
            } else {
                // Jika oldQuantity lebih kecil atau sama dengan quantity baru, kurangi stok
                $item->stock -= abs($quantityDifference);
            }

            // Simpan perubahan stok item
            $item->save();
        }

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteOutgoing(Request $request, $id)
    {
        $outgoingItem = OutgoingItem::findOrFail($id);
        $outgoingItem->delete();

        // Kurangi stock di Item yang sesuai
        $item = Item::where('item_code', $outgoingItem->item_code)->first();
        if ($item) {
            $item->stock += $outgoingItem->quantity;
            $item->save();
        }

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
