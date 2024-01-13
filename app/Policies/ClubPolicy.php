<?php

namespace App\Policies;

use TCG\Voyager\Contracts\User;
use TCG\Voyager\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Club;


class ClubPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * We can override all the BREAD 
     * (browse, read, edit, add and delete) 
     * actions here if we need to
     */
    public function edit(User $user, Club $club) {
        return $club->created_by === $user->id || $user->hasRole('admin');
    }

    public function read(User $user, Club $club) {
        return $club->created_by === $user->id || $user->hasRole('admin');
    }

    // public function delete(User $user, Club $club) {
    //     return $club->user_id === $user->id || $user->hasRole('admin');
    // }
}
