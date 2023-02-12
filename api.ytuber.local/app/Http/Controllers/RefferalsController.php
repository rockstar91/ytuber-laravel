<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class RefferalsController extends Controller
{
    public function get()
    {
        $user = auth::user();
        return \App\Models\User::paginate(10);
    }
    public function latest5()
    {
        $user = auth::user();
        return \App\Models\User::where('referrer_id',$user->id)->limit(5)->latest();
    }

}
