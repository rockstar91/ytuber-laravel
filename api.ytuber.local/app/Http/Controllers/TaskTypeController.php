<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskTypeController extends Controller
{
    public function getList(){
        return \App\Models\TaskType::where('extend',1)->get();
    }
}
