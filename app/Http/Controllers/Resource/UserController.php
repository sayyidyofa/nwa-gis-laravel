<?php


namespace App\Http\Controllers\Resource;


use App\Helper\CollectionHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:sadmin|admin');
    }

    public function index()
    {
        $users = null;//User::all();
        if (\Auth::user()->hasRole('sadmin')) { //There is Only ONE sadmin/superadmin
            $users = new User;
            // https://medium.com/@alariva/merging-eloquent-models-in-laravel-6c0fe82cc92b
            $users->fill(array_merge(User::role('user')->get()->toArray(), User::role('admin')->get()->toArray()));
            $users = $users->all()->except(Auth::id());
            $users = CollectionHelper::paginate($users, $users->count());
        }
        elseif (\Auth::user()->hasRole('admin')) {
            $users = User::role('user')->paginate(10);
        }
        return view('content.dashboard.user.index', compact('users'));
    }

    public function create()
    {
        return view('content.dashboard.user.create');
    }

    public function store(Request $request)
    {
        $role = Role::findByName($request->get('role') ?? 'user');
        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);
        $user->assignRole($role);
        $user->save();
        return response()->redirectTo('/user');
    }

    public function show(int $id)
    {
        //
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('content.dashboard.user.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = $request->get('password') ?? $user->password;
        $user->save();
        return response()->redirectTo('/user');
    }

    public function destroy(int $id)
    {
        try {
            User::findOrFail($id)->delete();
        } catch (\Exception $e) {
            dd('User not found');
        }
        return response()->redirectTo('/user');
    }
}
