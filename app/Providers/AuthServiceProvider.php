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
            return $can;
        });
        $gate->define('destroy-company', function ($user, \App\Company $company) {
            return true;
        });
        $gate->define('create-company', function ($user) {
            $cannot = $user->isViewer() || $user->isManager();
            return !$cannot;
        });

        // User
        $gate->define('show-user', function ($user) {
            return true;
        });
        $gate->define('create-user', function ($user) {
            return ($user->isOwner() || !$user->isViewer());
        });

        // end
    }
}
