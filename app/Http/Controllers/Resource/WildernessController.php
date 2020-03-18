<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Wilderness;
use Illuminate\Http\Request;

class WildernessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
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
        //
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
            return json_encode(array('status' => 'success'));
        }
        catch (\Exception $exception) {
            return json_encode(array('status' => 'failed'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wilderness  $wilderness
     * @return \Illuminate\Http\Response
     */
    public function show(Wilderness $wilderness)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wilderness  $wilderness
     * @return \Illuminate\Http\Response
     */
    public function edit(Wilderness $wilderness)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wilderness  $wilderness
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wilderness $wilderness)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wilderness  $wilderness
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wilderness $wilderness)
    {
        //
    }
}
