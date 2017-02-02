<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getReset($token)
    {
        return view('auth.reset', compact('token'));
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);
        try {
            $resetRecord = PasswordReset::where('token', $request->token)
                                        ->where('email', $request->email)
                                        ->firstOrFail();
        } catch (Exception $e) {
            session()->flash('danger', '邮箱错误，请重新输入！');

            return redirect()->route('password.edit', [$request->token]);
        }
        $user = User::where('email', $request->email)
            ->orderBy('created_at', 'DESC')
            ->firstOrFail();
        $user->password = $request->password;
        $user->save();
        Auth::login($user);

        session()->flash('success', '恭喜你，成功修改密码！');

        return redirect()->route('users.show', [$user]);
    }
}
