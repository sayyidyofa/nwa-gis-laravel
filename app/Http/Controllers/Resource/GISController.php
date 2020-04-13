<?php


namespace App\Http\Controllers\Resource;


use App\Geometry;
use App\Http\Controllers\Controller;
use App\Wilderness;
use Illuminate\Http\Request;

class GISController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:sadmin|admin|user');
    }

    public function create()
    {
        return view('content.dashboard.gis.create');
    }

    public function store(Request $request)
    {
        try {
            $w = new Wilderness([
                'name' => $request->name,
                'boundary_status' => $request->boundary_status,
                'color' => $request->color
            ]);
            $w->save();
            (new Geometry([
                'geotype' => $request->geotype,
                'coordinates' => $request->coordinates,
                'wildernesses_id' => $w->id
            ]))->save();
        } catch (\Exception $exception) {
            return response($exception, 500);
        }
        return response('success');
    }
}
