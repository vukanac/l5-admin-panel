<?php

namespace Tests;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyDeleteTest extends TestCase
{
	use DatabaseTransactions;
	
    
    public function test_users_can_delete_a_company()
    {
        $user = factory(User::class, 'admin')->create();

        $user->companies()->save($companyOne = factory(Company::class)->create());
        $user->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($user)
             ->visit('/companies')
             ->see($companyOne->name)
             ->see($companyTwo->name)
             ->press('delete-company-'.$companyOne->id)
             ->dontSeeInDatabase('companies', ['name' => $companyOne->name])
             ->dontSee($companyOne->name)
             ->see($companyTwo->name);
    }
    
    public function test_users_can_delete_companies_of_other_users()
    {
        // $this->withoutMiddleware();

        $userOne = factory(User::class, 'admin')->create();
        $userTwo = factory(User::class, 'admin')->create();

        $userOne->companies()->save($companyOne = factory(Company::class)->create());
        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->visit('/companies')
             ->see($companyOne->name)
             ->see($companyTwo->name)
             ->press('delete-company-'.$companyOne->id)
             ->press('delete-company-'.$companyTwo->id)
             ->dontSeeInDatabase('companies', ['name' => $companyOne->name])
             ->dontSeeInDatabase('companies', ['name' => $companyTwo->name])
             ->dontSee($companyOne->name)
             ->dontSee($companyTwo->name);

        //  REST =>> $this->delete('/company/'.$companyTwo->id)->assertResponseStatus(200);
    }
    
}
