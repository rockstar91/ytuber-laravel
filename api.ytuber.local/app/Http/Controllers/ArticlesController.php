<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function get($id){
        return \App\Models\Article::find($id);
    }
    public function getNews(){
        return \App\Models\News::orderBy('id', 'desc')->limit(4)->get();
        }
}
