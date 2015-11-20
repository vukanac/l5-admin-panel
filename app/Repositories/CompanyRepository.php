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
        return Company::orderBy('created_at', 'asc')
                    ->get();
    }
}
