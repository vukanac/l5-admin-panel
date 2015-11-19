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
    public function testIdNotExist()
    {
        $badId = '0';
        
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->get('/company/'.$badId)
             ->assertResponseStatus(404);
    }

    public function testShowOneCompanyDetails()
    {
        $user = factory(User::class, 'admin')->create();
        $user->companies()->save($company = factory(Company::class)->create());

        $this->actingAs($user)
             ->get('/company/'.$company->id)
             ->see($company->id);
    }

    public function test_users_cant_view_companiy_details_of_other_users()
    {
        $userOne = factory(User::class, 'admin')->create();
        $userTwo = factory(User::class, 'admin')->create();

        $userOne->companies()->save($companyOne = factory(Company::class)->create());
        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->get('/company/'.$companyTwo->id)
             ->assertResponseStatus(403);
    }
}
