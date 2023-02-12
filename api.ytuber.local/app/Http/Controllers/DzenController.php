<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DzenController extends Controller
{
    public function getLikesCountNow(Request $request){
        $dzen_video = 'https://dzen.ru/video/watch/636bbe7304b14236d752c703';
        $response = Http::get($dzen_video);
        return $response->body();
    }
}
