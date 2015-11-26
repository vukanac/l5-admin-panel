<?php

namespace App\Repositories;

use App\User;
use App\Company;

class CompanyRepository
{
    /**
     * Get all of the companies for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    public function forUser(User $user)
    {
        return Company::where('user_id', $user->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
    }


    /**
     * Get all of the companies rodered by name ascending
     *
     * @return Collection
     */
    public function getAllOrderedByNameAsc()
    {
        return Company::orderBy('name', 'asc')
                    ->get();
    }
}
