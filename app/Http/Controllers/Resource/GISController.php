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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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

    public function show($id) {
        return view('content.dashboard.gis.map', compact('id'));
    }

    public function export() {
        //$gis_all = GIS::all();
        return (new GISExport)->download('GIS.xlsx', null, ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET']);
    }

    public function import(Request $request) {
        $file = $request->file('file');
        // membuat nama file unik
        $nama_file = Carbon::now()->timestamp.'.'.$file->getClientOriginalExtension();
        $old_w_data = null;
        // upload ke folder file_siswa di dalam folder public
        $file->move('gis_imports', $nama_file);
        try {
            //if ()
            $old_w_data = Wilderness::all();
            Session::put('begin_w_id', Wilderness::latest()->first()->id + 1); // https://stackoverflow.com/questions/53503525/laravel-5-6-global-and-dynamic-variable
            (new WildernessesImport)->import(public_path('/gis_imports/'.$nama_file));
        } catch(\Exception $exception) {
            (new WildernessesImport)->import(public_path('/gis_imports/'.$nama_file));
            Session::put('begin_w_id', Wilderness::orderBy('id')->first()->id);
        }

        try {
            (new GeometriesImport)->import(public_path('/gis_imports/'.$nama_file));
        } catch (\Exception $exception) {
            if (isset($old_w_data) && $old_w_data->isNotEmpty()) { // https://stackoverflow.com/questions/20563166/eloquent-collection-counting-and-detect-empty
                $last_id = Wilderness::latest()->first()->id; // https://laracasts.com/discuss/channels/laravel/how-to-get-last-id-in-laravel
                for ($idx = $old_w_data->last()->id + 1; $idx <= $last_id; $idx++) {
                    Wilderness::findOrFail($idx)->delete();
                }
            } else {
                // https://stackoverflow.com/questions/29119272/laravel-eloquent-truncate-foreign-key-constraint
                Schema::disableForeignKeyConstraints();
                Wilderness::truncate(); // https://stackoverflow.com/questions/15484404/how-to-delete-all-the-rows-in-a-table-using-eloquent
                Schema::enableForeignKeyConstraints();
            }
            if ($exception instanceof QueryException)
                return response('Your uploaded dataset conflicts with current database! Try removing the WILDERNESS_ID column and import again', 422);
            else
                return response('Your uploaded dataset conflicts with current database! Unreadable dataset. Error: '.$exception, 422);
        }
        // TODO: fix bug -> files wont delete after importing
        //array_map('unlink', glob(public_path('/gis_imports/*')));
        //\File::delete('gis_imports/'.$nama_file);
        //\Storage::deleteDirectory('gis_imports');
        return response('success', 200);
    }
}
