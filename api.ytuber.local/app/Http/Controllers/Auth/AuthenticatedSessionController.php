<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    protected function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        $user = \App\Models\User::where('email',$request->input('email'))->first();
            if(!$user){
            //throw new \ErrorException('not founded');
                return response()->json(['message' => 'not founded'], 404);
                 }
            if($user->disabled == 1){
               // throw new \ErrorException('not activated');
                return response()->json(['message' => 'not activated'], 404);
                 }
            if($user->banned != 0){
               // throw new \ErrorException('banned');
                return response()->json(['message' => 'banned'], 404);
                }
        //if($user->password == $request->input('password') or Auth::attempt(['email' => $request->email, 'password' => $request->password], true)){
        if(auth::attempt($request->only('email','password'))){
            //auth::login($user);
            $request->session()->regenerate();
            return auth::user();
        }
        else{
            return response()->json(['status' => 'error','message' => 'Error','errors' => 'Не верный логин или пароль'],422);
        }
    }
    protected function logout()
    {
        Auth::logout();
    }
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
