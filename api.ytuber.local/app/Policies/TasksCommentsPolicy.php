<?php

namespace App\Policies;

use App\Models\TasksComments;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TasksCommentsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if($user->admin || $user->moderator){
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TasksComments  $tasksComments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TasksComments $tasksComments)
    {
        if($user->admin || $user->moderator){
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TasksComments  $tasksComments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TasksComments $tasksComments)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TasksComments  $tasksComments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TasksComments $tasksComments)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TasksComments  $tasksComments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TasksComments $tasksComments)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TasksComments  $tasksComments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TasksComments $tasksComments)
    {
        if($user->admin){
            return true;
        }
    }
}
