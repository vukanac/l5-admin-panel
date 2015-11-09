<?php

use App\User;
use App\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyDetailsTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }



    public function test_users_cant_view_companiy_details_of_other_users()
    {
    //     $userOne = factory(User::class)->create();
    //     $userTwo = factory(User::class)->create();

    //     $userOne->companies()->save($companyOne = factory(Company::class)->create());
    //     $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

    //     $this->actingAs($userOne)
    //          ->visit('/companies/'.$companyOne->id)
    //          ->seePageIs('/companies/'.$companyOne->id)
    //          ->see($companyOne->name)
    //          ->visit('/companies/'.$companyTwo->id)
    //          ->seePageIs('/companies')                  //  redirect if Unauthorized!
    //          ->dontSee($companyTwo->name);
    }

    public function testShowOneCompanyDetails()
    {
    	$user = factory(User::class)->create();
    	$user->companies()->save($company = factory(Company::class)->create());

        $this->get('/companies/'.$company->id)
             ->see($company->id);
             // ->see($company->name);
    }
}
