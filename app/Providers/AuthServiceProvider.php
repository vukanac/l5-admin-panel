<?php

namespace App\Providers;

use App\Company;
use App\Policies\CompanyPolicy;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Company::class => CompanyPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        // Note:
        // $user <--- logged user, global var
        // ----------------------------------

        $this->registerPolicies($gate);

        // Company
        $gate->define('show-company', function ($user, $company) {
            // user can see company details if:
            // - anyone
            return true;
        });
        $gate->define('update-company', function ($user, \App\Company $company) {
            $can = $user->isAdmin() || $user->isManager();
            $cannot = $user->isAuthor() || $user->isViewer();
            return $can || !$cannot;
        });
        $gate->define('destroy-company', function ($user, \App\Company $company) {
            $cannot = $user->isAuthor() || $user->isViewer();
            return !$cannot;
        });
        $gate->define('create-company', function ($user) {
            $cannot = $user->isViewer() || $user->isAuthor() || $user->isManager();
            return !$cannot;
        });

        // User
        $gate->define('show-user', function ($user) {
            return true;
        });
        $gate->define('create-user', function ($user) {
            if ($user->isViewer()) {
                return false;
            }
            if ($user->isAuthor()) {
                return false;
            }
            if ($user->isManager()) {
                return false;
            }
            if ($user->isAdmin()) {
                return true;
            }
            return $user->isOwner();
        });

        $gate->define('change-user-role', function ($user, \App\User $watchingUser = null) {
            if (isset($watchingUser) && $watchingUser->isOwner()) {
                return false;
            }
            $can = $user->isOwner() || $user->isAdmin();
            return $can;
        });
        $gate->define('destroy-user', function ($user, \App\User $watchingUser) {
            // $can = $user->isOwner() || $user->isAdmin() || $user->isManager() || $user->isAuthor() || $this->isViewer();
            // $cannot = false;
            
            // user cannot:
            // - destroy self
            // - destroy owner - nobody can destroy owner!!!

            if ($user->isViewer()) {
                return false;
            }
            if ($user->isAuthor()) {
                return false;
            }
            if ($user->isManager()) {
                return false;
            }
            if ($user->isAdmin()) {
                if($watchingUser->isOwner()) {
                    return false;
                }
                return true;
            }
            if ($user->isOwner()) {
                if ($watchingUser->isOwner()) {
                    return false;
                }
                return true;
            }
            // if($user->id === $watchingUser->id) {
            //     return false;
            // }
            

            return false;
        });
        $gate->define('update-user', function ($user, \App\User $watchingUser) {
            if ($user->id == $watchingUser->id) {
                // every user role can edit personal profile
                // can: [owner, admin, manager, author, viewer]
                // cannot: []
                return true;
            }
            
            if ($user->isViewer()) {
                return false;
            }
            if ($user->isAuthor()) {
                return false;
            }
            if ($user->isManager()) {
                if($watchingUser->isOwner()) {
                    return false;
                }
                return true;
            }
            if ($user->isAdmin()) {
                if ($watchingUser->isOwner()) {
                    return false;
                }
                return true;
            }
            if ($user->isOwner()) {
                return true;
            }
            return false;
        });
        
        // end
    }
}
