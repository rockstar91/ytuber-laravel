<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskReferralSources extends Controller
{
    public function getList(){
        
        return \App\Models\TaskReferralSource::all();
    }
}
