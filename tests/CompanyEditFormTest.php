<?php

namespace Tests;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyEditFormTest extends TestCase
{
    use DatabaseTransactions;
    

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_should_see_edit_button()
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

    //          ->see($company->name)
    //          ->type('Company New Name', 'name')
    //          ->press('Save Edit')
    //          ->seeInDatabase('companies', [
    //             'id' => $company->id,
    //             'name' => 'Company New Name'
    //             ]);
        
    }
}
