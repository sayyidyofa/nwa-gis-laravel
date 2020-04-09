<?php


namespace App\Http\Controllers\Resource;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GISController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:sadmin|admin|user', ['except' => ['index', 'show']]);
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(int $id)
    {
        //
    }

    public function edit(int $id)
    {
        //
    }

    public function update(Request $request, int $id)
    {
        //
    }

    public function destroy(int $id)
    {
        //
    }
}
