<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Wilderness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WildernessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:sadmin|admin|user');
    }

    public function create()
    {
        return response()->view('content.dashboard.wilderness.create');
    }

    public function store(Request $request)
    {
        /*$request->validate([
            'name' => ['required', 'string'],
            'boundary_status' => ['required', 'string']
        ]);*/
        try {
            $wilderness = new Wilderness;
            $wilderness->name = $request->name;
            $wilderness->boundary_status = $request->boundary_status;
            $wilderness->color = $request->color;
            $wilderness->save();
        }
        catch (\Exception $exception) {
            dd($exception);
        }
        return response()->view('content.dashboard.gis.create');
    }

    public function edit(int $id)
    {
        $w = Wilderness::findOrFail($id);
        return view('content.dashboard.wilderness.edit', compact('w'));
    }

    public function update(Request $request, int $id)
    {
        $w = Wilderness::findOrFail($id);
        $w->name = $request->get('name');
        $w->boundary_status = $request->get('boundary_status');
        $w->save();
        return response()->redirectTo('/dashboard/gisindex');
    }

    public function destroy(int $id)
    {
        try {
            return Wilderness::findOrFail($id)->delete();
        } catch (\Exception $e) {
            return response('error', 404);
        }
    }
}
