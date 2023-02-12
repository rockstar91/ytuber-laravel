<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksComments extends Model
{
    use HasFactory;

    protected $table = 'tasks_comments';

    public function Task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
    public function Account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }
}
