<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeChannels extends Model
{
    use HasFactory;

    protected $table = 'youtube_channels';

    public function getDataAttribute($value){
        if($value) {
            try {
                return unserialize($value);
            }
            catch(\Exception $e){}
        }
        else{
            return null;
        }

    }
}
