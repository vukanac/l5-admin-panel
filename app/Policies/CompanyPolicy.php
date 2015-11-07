<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given user can delete the given Company.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function destroy(\App\User $user, \App\Company $company)
    {
        return $user->id === $company->user_id;
    }
}
