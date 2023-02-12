<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskCategoryController extends Controller
{
    public function getList(){
        return \App\Models\TaskCategory::all();
    }
}
