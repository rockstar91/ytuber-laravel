<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModerateAccount extends Model
{
    use HasFactory;

    protected $table = 'moderated_accounts';

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
