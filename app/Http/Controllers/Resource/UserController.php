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
        $this->middleware('auth');
        $this->middleware('role:sadmin|admin');
    }

    public function index()
    {
        $users = null;//User::all();
        if (\Auth::user()->hasRole('sadmin')) { //There is Only ONE sadmin/superadmin
            $users = new User;
            // https://medium.com/@alariva/merging-eloquent-models-in-laravel-6c0fe82cc92b
            $users->fill(array_merge(User::role('user')->get()->toArray(), User::role('admin')->get()->toArray()));
            $users = $users->all();//->except(Auth::id());
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
        try {
            $role = Role::findByName($request->get('role') ?? 'user');
            $user = new User([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password'))
            ]);
            $user->assignRole($role);
            $user->save();
            \Session::flash('flash', json_encode(__('messages.success-create', ['model'=>'User'])));
            return response()->redirectToRoute('user.index');
        } catch (\Exception $exception) {
            \Session::flash('flash', json_encode(__('messages.error', ['model'=>'User', 'code'=>$exception->getCode()])));
            return response()->redirectToRoute('user.create');
        }
    }

    public function show(int $id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('content.dashboard.user.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = $request->get('password') ?? $user->password;
            $user->save();
            \Session::flash('flash', json_encode(__('messages.success-update', ['model'=>'User'])));
            return response()->redirectToRoute('user.index');
        } catch (\Exception $exception) {
            \Session::flash('flash', json_encode(__('messages.error', ['model'=>'User', 'code'=>$exception->getCode()])));
            return response()->redirectToRoute('user.edit', ['id' => $id]);
        }

    }

    public function destroy(int $id)
    {
        try {
            User::findOrFail($id)->delete();
            return response('success');
        } catch (\Exception $e) {
            return response('User not found', 400);
        }
    }
}
