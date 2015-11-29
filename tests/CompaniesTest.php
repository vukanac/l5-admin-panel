<?php

namespace Tests;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompaniesTest extends TestCase
{
    use DatabaseTransactions;

    
    public function test_companies_are_displayed_on_the_dashboard()
    {
        $user = factory(User::class, 'admin')->create();

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

    public function test_users_see_companies_of_other_users()
    {
        $userOne = factory(User::class, 'admin')->create();
        $userTwo = factory(User::class, 'admin')->create();

        $userOne->companies()->save($companyOne = factory(Company::class)->create());
        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->visit('/companies')
             ->see($companyOne->name)
             ->see($companyTwo->name);
    }
}
