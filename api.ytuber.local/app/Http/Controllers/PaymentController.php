<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function getMyPayments(){
        $user = Auth::user();
        $payments = \App\Models\Payments::where('user_id',$user->id)->latest()->paginate(10);
        return $payments;
    }
    public function getMethodsPayment(){
        return \App\Models\PaymentsSystems::all();
        }
    public function createPayment(Request $request){

        $user_id = auth::user()->id;
        $summa = $request->input('summa');
        $method = $request->input('method');
        $payment_system = \App\Models\PaymentsSystems::where('system',$method)->first();
        $payment = new \App\Models\Payments;
        $payment->user_id = $user_id;
        $payment->system = $payment_system->system;
        $payment->payment_system_id = $payment_system->id;
        $payment->type = $payment_system->name;
        $payment->commission = 0;
        $payment->sum = $summa;
        $payment->amount = $request->input('points');
        $payment->detail = "";
        $payment->status = false;
        $payment->created_at = Carbon::now();
        $payment->save();

        return $payment;
    }
    public function yoomoney(Request $request){
        Log::info($request->all());
        if($request->input('unaccepted') == 'true'){
            return "bad payment";
        }

        $secret_key = "SG6llb6SVFLHLT486DQwxgC+";
        $notification_type = $request->input('notification_type');
        $operation_id = $request->input('operation_id');
        $amount = $request->input('amount');
        $money = intval($request->input('amount'));
        $datetime = $request->input('datetime');
        $sender = $request->input('sender');
        $codepro = $request->input('codepro');
        $currency = $request->input('currency');
        $sha1_hash = $request->input('sha1_hash');
        $label = $request->input('label');
        $shaStr = $notification_type . '&'. $operation_id . '&' . $amount
            . '&'.$currency. '&' . $datetime . '&'. $sender . '&' . $codepro . '&'
            . $secret_key. '&' . $label;
        $sha1 = sha1($shaStr);
        if ($sha1 != $sha1_hash){

            Log::info("bad hash of payment");
            Log::info($request->all());
            return "bad hash of payment";

        }
        else{
            $payment_created = \App\Models\Payments::find($label);
            $user_id = $payment_created->user_id;
            $user = \App\Models\User::find($user_id);

            $factor = 10.5;
            if ($amount >= 100)
            {
                $factor = 12.5;
            }
            if ($amount >= 300)
            {
                $factor = 15.5;
            }
            if ($amount >= 490)
            {
                $factor = 22.5;
            }
            if ($amount >= 1200)
            {
                $factor = 26.25;
            }
            if ($amount >= 2100)
            {
                $factor = 30;
            }
            if ($amount >= 3100)
            {
                $factor = 34;
            }
            if ($amount >= 5100)
            {
                $factor = 38;
            }
            if ($amount >= 9100)
            {
                $factor = 42;
            }
            if ($amount >= 11000)
            {
                $factor = 44;
            }
            $points = ($money * $factor);

            $payment_created->user_id = $user->id;
            $payment_created->status = 1;
            $payment_created->amount = $points;
            $payment_created->detail = $sender;
            $payment_created->sum = $money;
            $payment_created->time = Carbon::now();
            $payment_created->update();

            $user->increment('balance',$points);
            $user->increment('rub',$money);

        }

    }
}
