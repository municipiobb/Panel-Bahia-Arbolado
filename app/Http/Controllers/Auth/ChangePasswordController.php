<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.passwords.change');
    }

    public function change(Request $request)
    {
        $this->validate($request, [
            'current_pwd' => 'required',
            'password' => 'required|confirmed|checkPassword',
        ]);

        if(! Hash::check($request->get('current_pwd'), auth()->user()->getAuthPassword())) {
            flash('La contraseña actual es incorrecta', 'danger');
            return back();
        }

        $this->resetPassword(auth()->user(), $request->get('password'));

        flash('Contraseña actualizada.', 'success');
        return back();
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'current_pwd', 'password', 'password_confirmation'
        );
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        $this->guard()->login($user);
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
