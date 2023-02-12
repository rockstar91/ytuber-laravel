<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class NotificationsController extends Controller
{
    public function getCompleted(){
        $user = Auth::user();
        return \App\Models\Notifications::where('user_id',$user->id)->with('TaskType')->limit(10)->latest()->get();
    }
    public function getCompletedToday(){
        $user = Auth::user();
        return \App\Models\Notifications::where('user_id',$user->id)->with('TaskType')->whereDate('created_at', Carbon::today())->get();
    }
}
