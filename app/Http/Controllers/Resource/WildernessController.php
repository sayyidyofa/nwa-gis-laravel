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
        $this->middleware('auth', ['except' => 'index', 'show']);
        $this->middleware('role:sadmin|admin|user', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('content.dashboard.wilderness.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|string
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'boundary_status' => ['required', 'string']
        ]);
        try {
            $wilderness = new Wilderness;
            $wilderness->name = $request->name;
            $wilderness->boundary_status = $request->boundary_status;
            $wilderness->save();
            //return json_encode(array('status' => 'success'));
        }
        catch (\Exception $exception) {
            dd($exception);
        }
        return response()->view('content.dashboard.geometry.create');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id)
    {
        //return Wilderness::findOrFail($id)->geometry()->coordinate;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $w = Wilderness::findOrFail($id);
        return view('content.dashboard.wilderness.edit', compact('w'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Wilderness $wilderness
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $w = Wilderness::findOrFail($id);
        $w->name = $request->get('name');
        $w->boundary_status = $request->get('boundary_status');
        $w->save();
        return response()->redirectTo('/dashboard/gisindex');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wilderness  $wilderness
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Wilderness $wilderness)
    {
        try {
            $wilderness->delete();
        } catch (\Exception $e) {
            dd($e);
        }
        return Redirect::to('/admin');
    }
}
