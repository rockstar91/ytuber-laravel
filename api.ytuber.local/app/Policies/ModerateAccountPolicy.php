<?php

namespace App\Policies;

use App\Models\ModerateAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModerateAccountPolicy
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
     * @param  \App\Models\ModerateAccount  $moderateAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ModerateAccount $moderateAccount)
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
     * @param  \App\Models\ModerateAccount  $moderateAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ModerateAccount $moderateAccount)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModerateAccount  $moderateAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ModerateAccount $moderateAccount)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModerateAccount  $moderateAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ModerateAccount $moderateAccount)
    {
        if($user->admin){
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModerateAccount  $moderateAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ModerateAccount $moderateAccount)
    {
        if($user->admin){
            return true;
        }
    }
}
