<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function GuzzleHttp\Promise\exception_for;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';

    public function TaskType()
    {
        return $this->belongsTo(TaskType::class, 'type_id', 'id');
    }

    public function TaskCategory()
    {
        return $this->hasOne(TaskCategory::class, 'id', 'category_id');
    }

    public function ReferralSources()
    {
        return $this->hasMany(TasksToReferralSources::class, 'task_id', 'id');
    }

    public function Comments()
    {
        return $this->hasMany(TasksComments::class, 'task_id', 'id');
    }
   public function Completions()
    {
        return $this->hasMany(Completions::class, 'task_id', 'id');
    }
   public function Completion()
    {
        return $this->hasMany(Completions::class, 'task_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getExtendAttribute($value){
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
    public function getYoutubeExtendAttribute($value){
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
