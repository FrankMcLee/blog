<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getEmail()
    {
        return view('auth.password');
    }

    public function postEmail(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $data = [
            'email' => $request->email,
            'token' => str_random(50),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $resetRecord = PasswordReset::create($data);

        $this->sendPasswordResetEmailTo($user, $resetRecord->token);
        session()->flash('success', '密码重置邮件已发出，请检查注册邮箱！');

        return redirect('/');
    }

    protected function sendPasswordResetEmailTo(User $user, $token)
    {
        $view = 'emails.password';
        $data = compact('token');
        $from = 'franklee@blog.app';
        $name = 'Frank Lee';
        $to = $user->email;
        $subject = 'Blog 应用用户密码找回';

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }
}
