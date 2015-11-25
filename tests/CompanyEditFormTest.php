<?php

use App\User;
use App\Company;

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
    public function testEdit()
    {
        //$this->withoutMiddleware();

        $user = factory(User::class, 'owner')->create();
        $company = factory(Company::class)->create();

        $user->companies()->save($company);
        

        $this->actingAs($user)
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'name' => $company->name,
                ])
             ->visit('/company/'.$company->id.'/edit')
             ->see($company->name)
             ->type('Company New Name', 'name')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'name' => 'Company New Name'
                ]);
    }
}
