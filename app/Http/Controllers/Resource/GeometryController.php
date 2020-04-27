<?php

namespace App\Http\Controllers\Resource;

use App\Geometry;
use App\Http\Controllers\Controller;
use App\Wilderness;
use Illuminate\Http\Request;

class GeometryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:sadmin|admin|user');
    }

    public function create(int $w_id) { // Requires Wilderness ID
        $w = Wilderness::findOrFail($w_id);
        return view('content.dashboard.geometry.create', compact('w'));
    }

    public function store(Request $request) {
        try {
            (new Geometry([
                'geotype' => $request->geotype,
                'coordinates' => $request->coordinates,
                'wildernesses_id' => $request->wilderness_id
            ]))->save();
            \Session::flash('flash', json_encode(__('messages.success-create', ['model'=>'Geometry'])));
            return response()->redirectToRoute('dashboard.gisindex');
        } catch (\Exception $exception) {
            \Session::flash('flash', json_encode(__('messages.error', ['model'=>'Geometry', 'code'=>$exception->getCode()])));
            return redirect()->back();
        }
    }

    public function edit(int $id)
    {
        $g = Geometry::findOrFail($id);
        $c = $g->coordinates;
        $t = $g->geotype;
        return view('content.dashboard.geometry.edit', compact('c', 't', 'id'));
    }

    public function update(Request $request, int $id)
    {
        try {
            $geometry = Geometry::findOrFail($id);
            $geometry->geotype = $request->geotype ?? $geometry->geotype;
            $geometry->coordinates = $request->coordinates;
            $geometry->wildernesses_id = $request->wildernesses_id ?? $geometry->wildernesses_id;
            $geometry->save();
            \Session::flash('flash', json_encode(__('messages.success-update', ['model'=>'Geometry'])));
            return response()->redirectToRoute('dashboard.gisindex');
        } catch (\Exception $exception) {
            \Session::flash('flash', json_encode(__('messages.error', ['model'=>'Geometry', 'code'=>$exception->getCode()])));
            return redirect()->back();
        }

    }

    public function destroy(int $id)
    {
        try {
            Geometry::findOrFail($id)->delete();
            return response('success');
        } catch (\Exception $e) {
            return response('error', 404);
        }
    }

    public function convert() {
        return view('content.dashboard.geometry.convert');
    }
}
