<?php


namespace App\Http\Controllers\Resource;


use App\Exports\GISExport;
use App\Geometry;
use App\Http\Controllers\Controller;
use App\Imports\GeometriesImport;
use App\Imports\WildernessesImport;
use App\Wilderness;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Session;

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

    /*public function show($id) {
        return view('content.dashboard.gis.map', compact('id'));
    }*/

    public function export() {
        //$gis_all = GIS::all();
        return (new GISExport)->download('GIS.xlsx', null, ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET']);
    }

    public function import(Request $request) {

        $this->validate($request, [
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ], ['Uploaded file is not a correct dataset file']);
        $file = $request->file('file');
        $old_w_data = null;
        $indexes = [];
        try {
            $old_w_data = Wilderness::all();
            (new WildernessesImport)->import($file->getPathName());
            $indexes = Wilderness::all()->diff($old_w_data)->pluck('id')->toArray();
        } catch(\Exception $exception) {
            (new WildernessesImport)->import($file->getPathName());
        }

        try {
            (new GeometriesImport)->import($file->getPathName());
        } catch (\Exception $exception) {
            debug($exception->getTrace());
            if (isset($old_w_data) && $old_w_data->isNotEmpty()) { // https://stackoverflow.com/questions/20563166/eloquent-collection-counting-and-detect-empty
                foreach ($indexes as $index) {
                    Wilderness::findOrFail($index)->delete();
                }
//                }
            } else {
                // https://stackoverflow.com/questions/29119272/laravel-eloquent-truncate-foreign-key-constraint
                Schema::disableForeignKeyConstraints();
                Wilderness::truncate(); // https://stackoverflow.com/questions/15484404/how-to-delete-all-the-rows-in-a-table-using-eloquent
                Schema::enableForeignKeyConstraints();
            }
            debug($exception->getTrace());
            if ($exception instanceof QueryException)
                return response('Your uploaded dataset conflicts with current database! Try removing the WILDERNESS_ID column and import again', 422);
            else
                return response('Your uploaded dataset conflicts with current database! Unreadable dataset. Error: '.$exception, 422);
        }
        return response('success', 200);
    }
}
