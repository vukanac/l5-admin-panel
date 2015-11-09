<?php

use App\User;
use App\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyDeleteTest extends TestCase
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

    public function test_users_can_delete_a_company()
    {
        $user = factory(User::class)->create([
            'name' => 'Vladimir Vukanac'
            ]);

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
    
    public function test_users_cant_delete_companies_of_other_users()
    {
        $this->withoutMiddleware();

        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $userOne->companies()->save($companyOne = factory(Company::class)->create());
        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->delete('/company/'.$companyTwo->id)
             ->assertResponseStatus(403);
    }
}
