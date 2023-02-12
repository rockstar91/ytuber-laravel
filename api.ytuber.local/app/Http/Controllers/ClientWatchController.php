<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ClientWatchController extends Controller
{
    public function getWatchVideo()
    {
        $user = auth::user();
        $account = $user->activeAccount;
        $account_reg_date = Carbon::now()->diffInDays($account->publishedAt);
        $account_videos = $account->videoCount;

        $tasksToWatch = \App\Models\Task::where('type_id',1)->where('disabled',0)->where('user_id','!=',$user->id)->whereDoesntHave('Completions', function ($query) use($user){
            $query->where('user_id',$user->id);
        })->whereHas('ReferralSources', function ($query) {
            $query->where('referral_source_id', '1')->orWhere('referral_source_id',2);
        })->where('channelAgeLimit', '<', Carbon::now()->subDays($account_reg_date)->diffInDays())->where('videosCountLimit','<=',$account_videos)->orderBy('action_cost', 'desc')->orderBy('targetTime','asc')->limit(25)->get();
        return $tasksToWatch;
    }
}
