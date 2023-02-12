<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RegistrationMailer;
use App\Mail\SupportMailer;
use Mail;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function index() {
        return view('feedback.index');
    }
    public function supportMail(Request $request){
        $user = auth::user();
        $data = new \stdClass();
        $data->title = $request->title;
        $data->user = 'https://api.ytuber.ru/nova/resources/users/'.$user->id;
        $data->email = $request->email;
        $data->message = $request->message;

        Mail::to('support@ytuber.ru')->send(new SupportMailer($data));

        return "sended";
    }
    public function send(Request $request) {

        $data = new \stdClass();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->message = $request->message;
        Mail::to($data->email)->send(new RegistrationMailer($data));
        return "Ваше сообщение успешно отправлено";
    }
}
