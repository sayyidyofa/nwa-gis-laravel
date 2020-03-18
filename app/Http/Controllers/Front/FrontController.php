<?php


namespace App\Http\Controllers\Front;

use App\Geometry;
use App\GIS;
use App\Http\Controllers\Controller;
use App\Wilderness;

class FrontController extends Controller
{
    // Public API
    public function indexGIS() {
        return response()->json(GIS::all());
    }

    //Single Pages
    public function homePage() {
        return view('content.front.homepage');
    }
}
