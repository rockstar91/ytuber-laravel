<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceWorkController extends Controller
{
/*    public function createUsers(){

        for($i = 0; $i<1000; $i++){
            $user = new \App\Models\User;
            $user->username = $i.'ytuber';
            $user->email = $i.'ytuber@ytuber.ru';
            $pass = str::random(32);
            $user->password_hash = $i.'ytuber@ytuber.ru';

            $data = [
                'email'=>$user->email,
                'password'=>$pass
            ];
            Log::info($data);
            $user->save();
        }
        return "created";

    }*/
    public function getChannelsForImages(){
        $user = auth::user();
        if($user->admin){
            return \App\Models\YoutubeChannels::all();
        }
    }
    public static function downloadImagesYoutube(){

        $channels = \App\Models\YoutubeChannels::all();

        foreach($channels as $channel){
            try {
                $url = $channel->data->snippet->thumbnails->default->url;
                if(!file_exists(public_path('images/' . $channel->data->id .'.jpg'))){
                    $client = new \GuzzleHttp\Client();
                    $client->request('GET', $url, [
                        'sink' => public_path('images/' . $channel->data->id .'.jpg'),
                        "proxy" => "http://DwKWvAGF:uWhnqCLN@77.90.150.198:48845",
                    ]);
            }
            }
            catch (\Exception $e) {}
        }

        return "downloaded";
    }
    public static function downloadChannelImg($id){

        $channel = \App\Models\YoutubeChannels::find($id);
        $url = $channel->data->snippet->thumbnails->default->url;

                    $client = new \GuzzleHttp\Client();
                    $client->request('GET', $url, [
                        'sink' => public_path('images/' . $channel->data->id .'.jpg'),
                        "proxy" => "http://DwKWvAGF:uWhnqCLN@77.90.150.198:48845",
                    ]);


        return "downloaded";
    }
}
