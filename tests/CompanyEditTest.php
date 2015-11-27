<?php

namespace Tests;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyFormTest extends TestCase
{
    use DatabaseTransactions;
    

    public function test_return_404_if_id_not_exist()
    {
        $badId = '0';
        
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->get('/company/'.$badId.'/edit')
             ->see('errors.not-found')
             ->see('Not found.')
             ->assertResponseStatus(404);
    }

    public function test_edit_one_company_details()
    {
        $user = factory(User::class, 'admin')->create();
        $user->companies()->save($company = factory(Company::class)->create());

        $this->actingAs($user)
             ->get('/company/'.$company->id.'/edit')
             ->see($company->id);
    }

    public function test_user_see_edit_button()
    {
        $user = factory(User::class, 'admin')->create();
        $company = factory(Company::class)->create();

        $user->companies()->save($company);

        $this->actingAs($user)
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'name' => $company->name,
                ])
             ->visit('/companies')
             ->see('edit-company-'.$company->id);
    }

    public function test_user_can_make_edit_to_own_company()
    {
        //$this->withoutMiddleware();

        $user = factory(User::class, 'admin')->create();
        $company = factory(Company::class)->create();

        $user->companies()->save($company);
        
        $companyNewName = 'Company Name ' . time();

        $this->actingAs($user)
             ->visit('/companies')
             ->see('edit-company-'.$company->id)
             ->click('edit-company-'.$company->id)
             ->see($company->name)
             ->type($companyNewName, 'name')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'name' => $companyNewName
                ])
             ->visit('companies')
             ->see($companyNewName);

    }
}