<?php


namespace App\Http\Controllers\Admin;


use App\GIS;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboardPage() {
        $geodata = GIS::paginate(5);
        $count = GIS::all()->count();
        return view('content.dashboard.gis.index', compact('geodata', 'count'));
    }
}
