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
        try {
            $wilderness = new Wilderness;
            $wilderness->name = $request->name;
            $wilderness->boundary_status = $request->boundary_status;
            $wilderness->color = $request->color;
            $wilderness->save();
            \Session::flash('flash', json_encode(__('messages.success-create', ['model'=>'Wilderness'])));
            return response()->redirectToRoute('dashboard.gisindex');
        }
        catch (\Exception $exception) {
            dd($exception);
            \Session::flash('flash', json_encode(__('messages.error', ['model'=>'Wilderness', 'code'=>$exception->getCode()])));
            return response()->redirectToRoute('wilderness.create');
        }
    }

    public function edit(int $id)
    {
        $w = Wilderness::findOrFail($id);
        return view('content.dashboard.wilderness.edit', compact('w'));
    }

    public function update(Request $request, int $id)
    {
        try {
            $w = Wilderness::findOrFail($id);
            $w->name = $request->get('name');
            $w->boundary_status = $request->get('boundary_status');
            $w->save();
            \Session::flash('flash', json_encode(__('messages.success-update', ['model'=>'Wilderness'])));
            return response()->redirectToRoute('dashboard.gisindex');
        } catch (\Exception $exception) {
            \Session::flash('flash', json_encode(__('messages.error', ['model'=>'Wilderness', 'code'=>$exception->getCode()])));
            return response()->redirectToRoute('wilderness.edit', ['id', $id]);
        }
    }

    public function destroy(int $id)
    {
        try {
            Wilderness::findOrFail($id)->delete();
            return response('success');
        } catch (\Exception $e) {
            return response('error', 404);
        }
    }
}
