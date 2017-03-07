<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Admins can view user info and list
     *
     * @param  \App\User  $userA admin
     * @param  \App\User  $userB subject
     * @return mixed
     */
    public function view(User $userA, User $userB)
    {
        return $userA->role === 'admin';
    }

    /**
     * Admins can create other users
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $userA->role === 'admin';
    }

    /**
     * Admins can update other users
     *
     * @param  \App\User  $user
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $userA, User $userB)
    {
        return $userA->role === 'admin';
    }

    /**
     * Admins can delete other users
     *
     * @param  \App\User  $user
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $userA, User $userB)
    {
        return $userA->role === 'admin';
    }
}
