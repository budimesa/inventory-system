<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ItemReportingController extends Controller
{
    public function index()
    {
        $items = MasterItem::all();
        return view('item_reporting.index', compact('items'));
    }

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
}
