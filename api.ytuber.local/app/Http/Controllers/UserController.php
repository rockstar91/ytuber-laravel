<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\RegistrationMailer;
use App\Mail\ResetPasswordMailer;
use App\Mail\NewPasswordMailer;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Mail;

class UserController extends Controller
{
    public function checkAccount()
    {
        $user = auth::user();
        $user->increment('bot_detection', 1);
        return "ok";
    }

    public function changeSettings(Request $request)
    {
        $user = auth::user();
        $password = $request->input('password');
        $newpassword = $request->input('newpassword');
        $newpassword2 = $request->input('newpassword2');

        if ($password) {
            if (Hash::check($password, $user->password)) {

                if ($newpassword!= null && $newpassword == $newpassword2) {
                    $user->password = bcrypt($newpassword2);
                    $user->update();
                }

            }
        }

        $allowtransaction = $request->input('allowtransaction');

        if ($allowtransaction == true) {
            $user->allow_transaction = 1;
            $user->update();
        }
        if ($allowtransaction == false) {

            $user->allow_transaction = 2;
            $user->update();
        }
        return "updated";
    }

    public function resetNewPassword(Request $request)
    {

        $newHash = str::random(16);
        $user = User::where('email', $request->input('email'))->where('reset_hash', $request->input('activate_hash'))->firstOrFail();
        $user->password = bcrypt($newHash);
        //$user->password_hash = bcrypt($newHash);
        $user->reset_hash = "";
        $user->update();

        $data = new \stdClass();
        $data->username = $user->username;
        $data->email = $request->input('email');
        $data->message = $newHash;

        Mail::to($request->input('email'))->send(new NewPasswordMailer($data));

        if ($user) {
            return redirect()->to('https://ytuber.ru/login');
        }
    }

    public function resetPassword(Request $request)
    {
        $user = \App\Models\User::where('email', $request->input('email'))->firstOrFail();
        $newHash = str::random(16);
        $user->reset_hash = $newHash;
        $user->update();

        $data = new \stdClass();
        $data->username = $user->username;
        $data->email = $request->input('email');
        $data->message = $newHash;

        Mail::to($request->input('email'))->send(new ResetPasswordMailer($data));

        return "reseted";


    }

    public function captchaValidate(Request $request)
    {
        //Проверяем запрос (Валидация)
        $validator = Validator::make(request()->all(), [
            'g-recaptcha-response' => 'recaptcha',
            // OR since v4.0.0
            recaptchaFieldName() => recaptchaRuleName()
        ]);

// check if validator fails
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        } else {
            return "validated";
        }
        //$token = $request->input('g-recaptcha-response');
        //
    }

    public function activateUser(Request $request)
    {

        $user = \App\Models\User::where('email', $request->input('email'))->where('activate_hash', $request->input('activate_hash'))->firstOrFail();
        $user->disabled = 0;
        $user->update();
        if ($user) {
            return redirect()->to('https://ytuber.ru/login');
        } else {
            return redirect()->to('https://ytuber.ru/login');
        }

    }

    public function register(Request $request)
    {
        //Проверяем запрос (Валидация)
        try {
            $this->validate(request(), [
                'username' => 'required|max:255',
                'email' => 'required|unique:users|max:255',
                'password' => 'required|max:255',
            ]);
        } catch (ValidationException $exception) {
            //В случае ошибки возвращаем запрос с ошибкой
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        $hashToValidate = str::random(32);

        $data = new \stdClass();
        $data->username = $request->input('username');
        $data->email = $request->input('email');
        $data->message = $hashToValidate;

        $newUser = new \App\Models\User;
        $newUser->username = $request->input('username');

        if ($request->input('email') == $request->input('email2')) {
            $newUser->email = $request->input('email');
        } else {
            return "not registered";
        }

        if ($request->input('password') == $request->input('password2')) {
            //$newUser->password = $request->input('password');
            $newUser->password = bcrypt($request->input('password'));
        } else {
            return "not registered";
        }
        $newUser->disabled = 1;
        $newUser->activate_hash = $hashToValidate;

        if ($request->input('refferal')) {
            $newUser->referrer_id = $request->input('refferal');
        }
        $newUser->save();

        Mail::to($request->input('email'))->send(new RegistrationMailer($data));

        return "registered";
    }

    public function user_balance()
    {
        return Auth::user();
    }

    public function user()
    {
        return Auth::user();
    }

    public function getUser()
    {
        $user = Auth::user();
        $userFind = \App\Models\User::with('activeAccount')->find($user->id);
        if ($userFind->banned) {
            Auth::logout();
        }
        return $userFind;
    }

    public function generateApi()
    {
        $user = Auth::user();
        $user->tokens->each->delete();
        $token = $user->createToken('user_access');
        $user->api_key = $token->plainTextToken;
        $user->update();
        return $token->plainTextToken;
    }

    public function getApiKey(Request $request)
    {
        $user = Auth::user();
        if ($user->api_key != null && $contains = Str::contains($user->api_key, '|')) {
            return $user->api_key;
        } else {
            $token = $user->createToken('user_access');
            $user->api_key = $token->plainTextToken;
            $user->update();
            return $token->plainTextToken;
        }
    }
}
