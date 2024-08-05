<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;
use App\Models\ProblematicItem;
use App\Models\AssetLoan;
use App\Models\Employee;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_items = MasterItem::count();
        $total_problematic_items = ProblematicItem::whereNull('return_date')->count();
        $total_asset_loans = AssetLoan::whereNull('return_date')->count();
        $total_employees = Employee::count();
        return view('home', compact('total_items', 'total_problematic_items', 'total_asset_loans', 'total_employees'));
    }
}
