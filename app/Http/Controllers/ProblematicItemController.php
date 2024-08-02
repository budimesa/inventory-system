<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\AssetLoan;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use App\Models\ProblematicItem;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ProblematicItemController extends Controller
{
    public function index()
    {
        $problematicItem = ProblematicItem::all();
        return view('problematic_items.index', compact('problematicItem'));
    }

    public function getProblematicItemList(Request $request)
    {
        if ($request->ajax()) {
            // Mendapatkan data barang bermasalah dan relasinya
            $data = ProblematicItem::leftJoin('asset_loans', 'problematic_items.asset_loan_id', '=', 'asset_loans.id')
                ->leftJoin('employees', 'asset_loans.employee_id', '=', 'employees.id')
                ->leftJoin('master_items', 'problematic_items.master_item_id', '=', 'master_items.id')
                ->select(
                    'problematic_items.*',
                    'employees.employee_name',
                    'employees.division',
                    'master_items.item_name',
                    'master_items.item_type',
                    'asset_loans.borrow_date'
                )
                ->orderBy('problematic_items.id', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('item_details', function ($row) {
                    // Mengambil item_name dan item_type dari relasi dan menggabungkannya
                    return $row->item_name . ' (' . $row->item_type . ')';
                })
                ->addColumn('status', function ($row) {
                    // Mapping status dari database ke nilai yang ingin ditampilkan
                    return $row->status == 'not_returned' ? 'Belum Dikembalikan' : 'Dikembalikan';
                })
                ->addColumn('action', function ($row) {
                    $isReturnDateSet = !is_null($row->return_date);
                    // Menentukan class disabled jika return_date ada
                    $disabledClass = $isReturnDateSet ? 'disabled' : '';
                    // Membuat tombol-tombol dengan kondisi disabled
                    $returnButton = $isReturnDateSet ?
                        '<a class="btn modal-effect text-primary btn-sm btn-return ' . $disabledClass . '" data-toggle="modal" href="#Umodaldemo9" data-toggle="tooltip" data-original-title="Return" data-return=\'' . json_encode($row) . '\'><span class="fas fa-reply text-success fs-14"></span></a>' :
                        '<a class="btn modal-effect text-primary btn-sm btn-return" data-toggle="modal" href="#Umodaldemo9" data-toggle="tooltip" data-original-title="Return" data-return=\'' . json_encode($row) . '\'><span class="fas fa-reply text-success fs-14"></span></a>';
        
                    // Mengembalikan tombol aksi
                    return '<div class="g-2">' . $returnButton . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function returnProblematicItem(Request $request, $id)
    {
        $problematicItem = ProblematicItem::findOrFail($id);

        $problematicItem->update([
            'status'       => 'returned',
            'return_date'     => $request->return_date,
            'notes'        => $request->notes,
            'received_by'   => Auth::user()->name,
        ]);

        $item = MasterItem::find($problematicItem->master_item_id);
        $item->stock += 1;
        $item->save();

        return response()->json(['success' => 'Asset Loan updated successfully.']);
    }
}
