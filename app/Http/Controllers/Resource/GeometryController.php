<?php

namespace App\Http\Controllers\Resource;

use App\Geometry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeometryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index', 'show']);
        $this->middleware('role:sadmin|admin|user', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Geometry::all());
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
     * @return string
     */
    public function store(Request $request)
    {
        $request->validate([
            'geotype' => ['required', 'string'],
            'coordinates' => ['required', 'string'],
            'wildernesses_id' => ['required', 'int']
        ]);
        try {
            $geometry = new Geometry;
            $geometry->geotype = $request->geotype;
            $geometry->coordinates = $request->coordinates;
            $geometry->wildernesses_id = $request->wildernesses_id;
            $geometry->save();
            return json_encode(array('status' => 'success'));
        }
        catch (\Exception $exception) {
            return json_encode(array('status' => 'failed'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Geometry  $geometry
     * @return \Illuminate\Http\Response
     */
    public function show(Geometry $geometry)
    {
        //
    }

    /*public function showCoords(int $id) {
        return Geometry::findOrFail($id)->coordinates;
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Geometry  $geometry
     * @return \Illuminate\Http\Response
     */
    public function edit(Geometry $geometry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Geometry  $geometry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Geometry $geometry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Geometry  $geometry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Geometry $geometry)
    {
        //
    }
}
