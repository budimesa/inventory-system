<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\MasterItem;
use Yajra\DataTables\Facades\DataTables;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use Illuminate\Support\Facades\DB;

class ItemReportingController extends Controller
{
    public function index()
    {
        $items = MasterItem::all();
        return view('item_reporting.index', compact('items'));
    }

    // public function getItemReport(Request $request)
    // {

    //     if ($request->ajax()) {
    //         $startDate = $request->start_date;
    //         $endDate = $request->end_date;
    
    //         // Subquery untuk total incoming
    //         $incomingSubQuery = IncomingItem::select('item_code', DB::raw('SUM(quantity) AS total_incoming'))
    //             ->groupBy('item_code');
    
    //         // Subquery untuk total outgoing
    //         $outgoingSubQuery = OutgoingItem::select('item_code', DB::raw('SUM(quantity) AS total_outgoing'))
    //             ->groupBy('item_code');
    
    //         // Query utama untuk mengambil data item
    //         $query = Item::select('items.*')
    //             ->leftJoinSub($incomingSubQuery, 'incoming', 'items.item_code', '=', 'incoming.item_code')
    //             ->leftJoinSub($outgoingSubQuery, 'outgoing', 'items.item_code', '=', 'outgoing.item_code')
    //             ->selectRaw('COALESCE(incoming.total_incoming, 0) AS total_incoming')
    //             ->selectRaw('COALESCE(outgoing.total_outgoing, 0) AS total_outgoing')
    //             ->selectRaw('items.stock + COALESCE(incoming.total_incoming, 0) - COALESCE(outgoing.total_outgoing, 0) AS total_stock')
    //             ->orderBy('items.id', 'DESC');
    
    //         // Filter by date range
    //         if ($startDate && $endDate) {
    //             $query->whereBetween('items.created_at', [$startDate, $endDate]);
    //         }
    
    //         $data = $query->get();
    
    //         return DataTables::of($data)
    //             ->addColumn('action', function($row){
    //                 $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger">Delete</a>';
    //                 return $actionBtn;
    //             })
    //             ->rawColumns(['action'])
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }

    public function getItemReport(Request $request)
    {
        if ($request->ajax()) {
            
            $data = MasterItem::withCounts()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('borrowed_count', function ($row) {
                    return $row->borrowed_count;
                })
                ->addColumn('problematic_count', function ($row) {
                    return $row->problematic_count;
                })
                ->addColumn('total_stock', function ($row) {
                    return $row->total_stock;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show($id)
    {
        // 
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        // 
    }

    public function edit(Item $item)
    {
        // 
    }

    public function update(Request $request, Item $item)
    {
        // 
    }

    public function destroy(Item $item)
    {
        // 
    }
}
