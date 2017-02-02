<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 01/02/2017
 * Time: 2:09 PM
 */
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [
                'update',
                'edit',
                'destroy'
            ]
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(30);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:6|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user->id]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update($id, Request $request)
    {
        $rule = empty($request->password) ? [
            'name' => 'required|max:50',
        ] : [
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6'
        ];
        $this->validate($request, $rule);
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $data = array_filter([
            'name' => $request->name,
            'password' => $request->password,
        ]);
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $id);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户' . $user->name);

        return back();
    }
}
