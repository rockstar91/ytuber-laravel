<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completions extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function task()
    {
        return $this->belongsTo('App\Models\Task', 'task_id', 'id');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id', 'id');
    }
    public function TaskType()
    {
        return $this->hasOne(TaskType::class, 'id', 'type_id');
    }
}
