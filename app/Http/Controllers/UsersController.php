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
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [
                'update',
                'edit',
                'destroy',
                'followings',
                'followers'
            ]
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index()
    {
        $this->authorize('userIndex', Auth::user());
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
        $statuses = $user->statuses()
                         ->orderBy('created_at', 'desc')
                         ->paginate(30);

        return view('users.show', compact('user', 'statuses'));
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
        $this->sendConfirmationEmailTo($user);
        session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');

        return redirect('/');
    }

    protected function sendConfirmationEmailTo(User $user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'mc.franklee@gmail.com';
        $name = 'Frank Lee';
        $to = $user->email;
        $subject = '感谢注册 Blog 应用！请确认你的邮箱。';

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)
                    ->to($to)
                    ->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)
                    ->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');

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

    public function followings($id)
    {
        $users = User::findOrFail($id)
                     ->followings()
                     ->paginate(30);
        $title = '关注的人';

        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers($id)
    {
        $users = User::findOrFail($id)
                     ->followers()
                     ->paginate(30);
        $title = '粉丝';

        return view('users.show_follow', compact('users', 'title'));
    }
}
