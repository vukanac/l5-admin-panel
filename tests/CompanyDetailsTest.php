<?php

namespace Test;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyDetailsTest extends TestCase
{
    use DatabaseTransactions;
    
    public function test_responds_404_if_id_does_not_exist()
    {
        $badId = '0';
        
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->get('/company/'.$badId)
             ->see('errors.not-found')
             ->see('Not found.')
             ->assertResponseStatus(404);
    }

    public function test_company_details_can_be_seen()
    {
        $user = factory(User::class, 'admin')->create();
        $user->companies()->save($company = factory(Company::class)->create());

        $this->actingAs($user)
             ->get('/company/'.$company->id)
             ->see($company->id)
             ->assertResponseStatus(200);
    }

    public function test_company_details_of_other_user_can_be_seen()
    {
        $userOne = factory(User::class, 'admin')->create();
        $userTwo = factory(User::class, 'admin')->create();

        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->get('/company/'.$companyTwo->id)
             ->assertResponseStatus(200);
    }
}
