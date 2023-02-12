<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsTasksController extends Controller
{
    public function getFree($id){
        $task_id = $id;
        $user = \App\Models\User::find(Auth::user()->id);

        $account_id = $user->activeAccount->id;
  
        $find_comment = \App\Models\TasksComments::where('task_id',$id)->where('account_id',$account_id)->first();
        if($find_comment){
            return $find_comment;
        }
        else{
            $comment = \App\Models\TasksComments::where('task_id',$id)->where('status',0)->inRandomOrder()->first();
            if($comment){
                $comment->account_id = $account_id;
                $comment->update();
                return $comment;
            }
            else{
                return "no comments";
            }
        }
    }
}
