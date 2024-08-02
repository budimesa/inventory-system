<?php

namespace App\Http\Controllers;

use App\Models\AssetLoan;
use App\Models\MasterItem;
use App\Models\ProblematicItem;
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

            $query = AssetLoan::leftJoin('employees', 'asset_loans.employee_id', '=', 'employees.id')
            ->select('asset_loans.*', 'employees.employee_name', 'employees.division')
            ->with('masterItems') // Mendapatkan item yang berhubungan melalui pivot table
            ->orderBy('asset_loans.id', 'DESC');

        // Filter transaksi mendekati jatuh tempo
        if ($request->due_soon) {
            $query->where('planned_return_date', '>=', Carbon::now()->startOfDay())
            ->where('planned_return_date', '<=', Carbon::now()->addDays(2)->endOfDay());
        }

        if ($request->late) {
            $query->where('planned_return_date', '<=', Carbon::now());
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('item_details', function ($row) {
                // Mengambil item_name dan item_type dari relasi dan menggabungkannya
                return $row->masterItems->map(function ($item) {
                    return $item->item_name . ' (' . $item->item_type .')';
                })->implode(', ');
            })
            ->addColumn('action', function ($row) {
                $isReturnDateSet = !is_null($row->return_date);

                // Menentukan class disabled jika return_date ada
                $disabledClass = $isReturnDateSet ? 'disabled' : '';
        
                // Membuat tombol-tombol dengan kondisi disabled
                $editButton = $isReturnDateSet ? 
                    '<a class="btn modal-effect text-primary btn-sm btn-edit ' . $disabledClass . '" data-toggle="modal" href="#Umodaldemo8" data-toggle="tooltip" data-original-title="Edit" data-edit=\'' . json_encode($row) . '\'><span class="fas fa-edit text-success fs-14"></span></a>' :
                    '<a class="btn modal-effect text-primary btn-sm btn-edit" data-toggle="modal" href="#Umodaldemo8" data-toggle="tooltip" data-original-title="Edit" data-edit=\'' . json_encode($row) . '\'><span class="fas fa-edit text-success fs-14"></span></a>';
        
                $returnButton = $isReturnDateSet ?
                    '<a class="btn modal-effect text-primary btn-sm btn-return ' . $disabledClass . '" data-toggle="modal" href="#Umodaldemo9" data-toggle="tooltip" data-original-title="Return" data-return=\'' . json_encode($row) . '\'><span class="fas fa-reply text-success fs-14"></span></a>' :
                    '<a class="btn modal-effect text-primary btn-sm btn-return" data-toggle="modal" href="#Umodaldemo9" data-toggle="tooltip" data-original-title="Return" data-return=\'' . json_encode($row) . '\'><span class="fas fa-reply text-success fs-14"></span></a>';
        
                // Mengembalikan tombol aksi
                return '<div class="g-2">' . $editButton . ' ' . $returnButton . '</div>';
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
    
        $assetLoan->masterItems()->attach($request->master_item_id);
    
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

    public function returnLoan(Request $request, $id)
    {
        
        $AssetLoan = AssetLoan::findOrFail($id);
        $AssetLoan->update([
            'notes'   => $request->notes,
            'return_date'     => $request->return_date,
            'received_by'   => $request->received_by,
        ]);
        // Ambil master_item_id lama sebelum diupdate
        $originalItemIds = $AssetLoan->masterItems->pluck('id')->toArray();
        $returnedItemIds = $request->master_item_id;

        foreach ($returnedItemIds as $itemId) {
            $item = MasterItem::find($itemId);
            $item->stock += 1;
            $item->save();

            // $AssetLoan->masterItems()->detach($itemId);
        }

        // Tangani item yang tidak dikembalikan
        $notReturnedItemIds = array_diff($originalItemIds, $returnedItemIds);
        foreach ($notReturnedItemIds as $itemId) {
            ProblematicItem::updateOrCreate(
                ['asset_loan_id' => $AssetLoan->id, 'master_item_id' => $itemId],
                ['status' => 'not_returned']
            );
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
