<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function TaskType()
    {
        return $this->hasOne(TaskType::class, 'id', 'task_type_id');
    }
}
