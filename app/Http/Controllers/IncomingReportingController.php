<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class IncomingReportingController extends Controller
{
    public function index()
    {
        $incomingItems = IncomingItem::all();
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('incoming_reporting.index', compact('incomingItems', 'suppliers', 'items'));
    }

    public function getIncomingReport(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query = IncomingItem::leftJoin('suppliers', 'incoming_items.supplier_id', '=', 'suppliers.id')
                                ->leftJoin('items', 'incoming_items.item_code', '=', 'items.item_code')
                                ->select('incoming_items.*', 'suppliers.name as supplier_name', 'items.name as item_name')
                                ->orderBy('id', 'DESC');
            // Filter by date range
            if ($startDate && $endDate) {
                $query->whereBetween('incoming_items.incoming_date', [$startDate, $endDate]);
            }
    
            $data = $query->get();
            
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
        // 
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
        // 
    }

    public function destroy($id)
    {
        // 
    }
}
