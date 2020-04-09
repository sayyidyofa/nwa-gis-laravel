<?php


namespace App\Http\Controllers\Dashboard;


use App\GIS;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:sadmin|admin|user');
    }

    public function dashboardPage() {
        return view('content.dashboard.home');
    }

    public function gisIndexPage() {
        $geodata = GIS::paginate(10);
        $count = GIS::all()->count();
        return view('content.dashboard.gis.index', compact('geodata', 'count'));
    }

}