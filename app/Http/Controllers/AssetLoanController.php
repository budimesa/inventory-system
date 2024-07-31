<?php

namespace App\Http\Controllers;

use App\Models\AssetLoan;
use App\Models\MasterItem;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AssetLoanController extends Controller
{
    public function index()
    {
        $assetLoans = AssetLoan::all();
        $divisions =  Employee::select('division', DB::raw('count(*) as total'))
        ->groupBy('division')
        ->get();
        $master_items = MasterItem::where('stock', '>', 0)->get();
        return view('asset_loans.index', compact('assetLoans', 'divisions', 'master_items'));
    }

    public function getLoanList(Request $request)
    {
        if ($request->ajax()) {
            // Mendapatkan data pinjaman aset dan relasinya
            $data = AssetLoan::leftJoin('employees', 'asset_loans.employee_id', '=', 'employees.id')
                ->select('asset_loans.*', 'employees.employee_name', 'employees.division')
                ->with('masterItems') // Mendapatkan item yang berhubungan melalui pivot table
                ->orderBy('asset_loans.id', 'DESC')
                ->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('item_details', function ($row) {
                    // Mengambil item_name dan item_type dari relasi dan menggabungkannya
                    return $row->masterItems->map(function ($item) {
                        return $item->item_name . ' (' . $item->item_type .')';
                    })->implode(', ');
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
                ->addColumn('incoming_date', function ($row) {
                    return Carbon::parse($row->incoming_date)->format('Y-m-d');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        // Method to show the create form
    }

    public function store(Request $request)
    {
        $assetLoan = AssetLoan::create([
            'employee_id' => $request->employee,
            'borrow_date' => $request->borrow_date,
            'planned_return_date' => $request->planned_return_date,
            'loan_reason' => $request->loan_reason,
        ]);
    
        $assetLoan->masterItems()->attach($request->item_id);
    
        foreach ($request->master_item_id as $itemId) {
            $item = MasterItem::find($itemId);
    
            if ($item) {
                $item->stock -= 1;
                $item->save();
            }
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

        $AssetLoan = AssetLoan::findOrFail($id);
        $AssetLoan->update($request->all());

        return redirect()->route('incoming_items.index')
            ->with('success', 'Incoming item updated successfully.');
    }

    public function updateLoan(Request $request, $id)
    {
        $AssetLoan = AssetLoan::findOrFail($id);
        // Ambil master_item_id lama sebelum diupdate
        $oldItemIds = $AssetLoan->masterItems->pluck('id')->toArray();
        $newItemIds = $request->master_item_id;

        // Update data pinjaman
        $AssetLoan->update([
            'division'   => $request->division,
            'employee_id'       => $request->employee_id,
            'borrow_date'     => $request->borrow_date,
            'planned_return_date'        => $request->planned_return_date,
            'loan_reason'   => $request->loan_reason,
        ]);

         // Loop melalui master_item_id baru dan bandingkan dengan master_item_id lama
        foreach ($oldItemIds as $oldItemId) {
            if (!in_array($oldItemId, $newItemIds)) {
                // Kembalikan stok jika master_item_id lama tidak ada di master_item_id baru
                $item = MasterItem::find($oldItemId);
                $item->stock += 1;
                $item->save();

                // Hapus dari tabel pivot
                $AssetLoan->masterItems()->detach($oldItemId);
            }
        }

        foreach ($newItemIds as $newItemId) {
            if (!in_array($newItemId, $oldItemIds)) {
                // Kurangi stok jika master_item_id baru tidak ada di master_item_id lama
                $item = MasterItem::find($newItemId);
                $item->stock -= 1;
                $item->save();

                // Tambahkan ke tabel pivot
                $AssetLoan->masterItems()->attach($newItemId);
            }
        }

        return response()->json(['success' => 'Asset Loan updated successfully.']);
    }

    public function deleteIncoming(Request $request, $id)
    {
        $assetLoan = AssetLoan::findOrFail($id);
        $assetLoan->delete();

        // Kurangi stock di Item yang sesuai
        $item = MasterItem::where('item_code', $assetLoan->item_code)->first();
        if ($item) {
            $item->stock -= $assetLoan->quantity;
            $item->save();
        }

        return response()->json(['message' => 'Data deleted successfully']);
    }

    public function destroy($id)
    {
        $assetLoan = AssetLoan::findOrFail($id);
        $assetLoan->delete();

        return redirect()->route('incoming_items.index')
            ->with('success', 'Incoming item deleted successfully.');
    }
}
