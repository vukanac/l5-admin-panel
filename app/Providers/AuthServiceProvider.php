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
            $cannot = $user->isAuthor();
            return $can || !$cannot;
        });
        $gate->define('destroy-company', function ($user, \App\Company $company) {
            $cannot = $user->isAuthor();
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
            return ($user->isOwner() || !$user->isViewer());
        });

        $gate->define('change-user-role', function ($user) {
            $can = $user->isOwner() || $user->isAdmin();
            return $can;
        });
        $gate->define('destroy-user', function ($user, \App\User $watchingUser) {
            // $can = $user->isOwner() || $user->isAdmin() || $user->isManager() || $user->isAuthor() || $this->isViewer();
            // $cannot = false;
            
            // user cannot:
            // - destroy self
            // - destroy owner

            if($user->isOwner()) {
                return true;
            }
            // if($user->id === $watchingUser->id) {
            //     return false;
            // }
            // if($watchingUser->isOwner()) {
            //     return false;
            // }

            return false;
        });
        // end
    }
}
