<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;
use App\Mail\TransactionPointsMailer;


class TransactionsController extends Controller
{
    public function allowTransaction(Request $request,$id){

        //перевод
        $transaction = \App\Models\Transaction::find($id);
        //кто переводит
        $user = \App\Models\User::find($transaction->sender);
        //сумма перевода


        $totalSend = $transaction->sum;
        //Кто переводит
        $recipient = $transaction->recipient;
        $userToSend = \App\Models\User::find($recipient);

        //Если транзакция еще не проведена
        if($transaction->status == 0) {
            
            $hash = $request->input('hash');
            if($hash != $user->refresh_token){
                return "wrong hash";
            }

            //Если хватает баллов
            if ($user->balance > 0 && $user->balance >= $totalSend) {
                $transaction->status = 1;
                $transaction->update();

                $userToSend->increment('balance', $totalSend);
                $user->decrement('balance', $totalSend);

                $newHash = str::random(32);
                $user->refresh_token = $newHash;
                $user->update();

                return $transaction;
            }
            else{
                return "not completed";
            }
        }
        else{
            return $transaction;
        }
        //return redirect()->to('https://ytuber.ru/transactions');

    }
    public function get(){
        $user = auth::user();
        $transactions = \App\Models\Transaction::where('sender',$user->id)->orWhere('recipient',$user->id)->orderBy('time','DESC')->paginate(10);
        return $transactions;
    }
    public function make(Request $request){

        $user = auth::user();

        $idToSend = $request->input('id');
        $totalSend = $request->input('balance');

        $userToSend = \App\Models\User::find($idToSend);

        $newHash = str::random(32);
        $user->refresh_token = $newHash;
        $user->update();

        if($userToSend) {
            if ($user->balance > 0 && $user->balance >= $totalSend) {

                //Создаём запись о переводах
                $t = new \App\Models\Transaction;
                $t->sender = $user->id;
                $t->recipient = $idToSend;
                $t->sum = $totalSend;
                $t->time = carbon::now();
                $t->save();

                //Создаём письмо
                $data = new \stdClass();
                $data->points = $totalSend;
                $data->account_id = $idToSend;
                $data->hash = $newHash;
                $data->id = $t->id;
                $data->name = $user->name;
                $data->email = $user->email;

                //отправляем письмо с хешем подтверждения
                if($user->allow_transaction == 1) {
                    Mail::to($user->email)->send(new TransactionPointsMailer($data));
                    return "sended";
                }
                if($user->allow_transaction == 2) {
                    return '/allow-transaction/' . $t->id .'?hash=' .$newHash;
                }

            } else {
                return "no money";
            }
        }
        else{
            return "user not founded";
        }
    }
}
