<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'transactions';
    protected $casts = ['time' => 'datetime'];

    public function user_sender()
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }
    public function user_recipient()
    {
        return $this->belongsTo(User::class, 'recipient', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }
}
