<?php

use App\User;
use App\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompaniesTest extends TestCase
{
    use DatabaseTransactions;

    
    public function test_companies_are_displayed_on_the_dashboard()
    {
        $user = factory(User::class)->create();

        // factory(Company::class)->create(['name' => 'Company 1']);
        // factory(Company::class)->create(['name' => 'Company 2']);
        // factory(Company::class)->create(['name' => 'Company 3']);

        $user->companies()->save($companyOne = factory(Company::class)->create(['name' => 'Company 1']));
        $user->companies()->save($companyTwo = factory(Company::class)->create(['name' => 'Company 2']));
        $user->companies()->save($companyThree = factory(Company::class)->create(['name' => 'Company 3']));


        $this->actingAs($user)
             ->visit('/companies')
             ->seePageIs('companies')
             ->see('Company 1')
             ->see('Company 2')
             ->see('Company 3');
    }

    public function test_i_am_redirect_to_login_if_i_try_to_view_company_lists_without_logging_in()
    {
        // accessible only for logged user
        $this->visit('/companies')
             ->see('Login')
             ->seePageIs('auth/login');
    }

    public function test_users_cant_view_companies_of_other_users()
    {
        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $userOne->companies()->save($companyOne = factory(Company::class)->create());
        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->visit('/companies')
             ->see($companyOne->name)
             ->dontSee($companyTwo->name);
    }

}
