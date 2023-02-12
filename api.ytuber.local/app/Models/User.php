<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Questocat\Referral\Traits\UserReferral;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'password_hash',
        'ip',
        'email',
        'api_key',
        'remember_token',
        'activate_hash',
        'refresh_token',
        'reset_hash',
        'forgot_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance' => 'integer',
    ];
    public function tasks(){
        return $this->hasMany(Task::class,'user_id','id');
    }
    public function completions(){
        return $this->hasMany(Completions::class,'user_id','id');
    }
    public function account(){
        return $this->hasMany(Account::class,'user_id','id');
    }
    public function accounts(){
        return $this->hasMany(Account::class,'user_id','id');
    }
    public function activeAccount(){
        return $this->HasOne(Account::class,'user_id','id')->where('active',1);
    }
    public function rooms(){
        return $this->belongsToMany(Room::class);
    }
    public function transactions(){
            return $this->hasMany(Transaction::class,'sender','id');
    }
    public function payment(){
            return $this->hasMany(Payments::class,'user_id','id');
    }
    public function recipients(){
            return $this->hasMany(Transaction::class,'recipient','id');
    }
}
