<?php

namespace App\Http\Controllers;

use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Item;
use Yajra\DataTables\Facades\DataTables;

class OutgoingReportingController extends Controller
{
    public function index()
    {
        $outgoingItems = OutgoingItem::all();
        $items = Item::all();
        return view('outgoing_reporting.index', compact('outgoingItems', 'items'));
    }

    public function getOutgoingReport(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query = OutgoingItem::orderBy('id', 'DESC');
            
            // Filter by date range
            if ($startDate && $endDate) {
                $query->whereBetween('outgoing_items.outgoing_date', [$startDate, $endDate]);
            }
    
            $data = $query->get();
            
            return DataTables::of($data)
                ->addIndexColumn()                
                ->addColumn('outgoing_date', function ($row) {
                    return Carbon::parse($row->outgoing_date)->format('Y-m-d');
                })                    
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
        // Method to show a specific outgoing item (optional)
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
