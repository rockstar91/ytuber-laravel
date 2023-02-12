<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $casts = ['publishedAt' => 'datetime'];

    public function AccountType(){
        return $this->hasOne(AccountType::class,'id','account_type_id');
    }
    public function TasksComments(){
        return $this->hasMany(TasksComments::class,'account_id','id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
