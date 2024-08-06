<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;
use App\Models\ProblematicItem;
use App\Models\AssetLoan;
use App\Models\Employee;
use Carbon\Carbon;


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
        // $total_late = AssetLoan::whereNotNull('return_date')
        // ->where('planned_return_date', '<', 'return_date')
        // ->count();
        $today = Carbon::today();
        $dueSoonDate = $today->addDays(2);

        $total_due_soon = AssetLoan::whereBetween('planned_return_date', [$today, $dueSoonDate])->count();
        return view('home', compact('total_items', 'total_problematic_items', 'total_asset_loans', 'total_employees',  'total_due_soon'));
    }
}
