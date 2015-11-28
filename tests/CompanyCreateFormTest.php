<?php

namespace Tests;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyCreateFormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_companies_can_be_created_from_form()
    {
        $user = factory(User::class, 'owner')->create();
        $this->actingAs($user);
        
        $companyName = 'Company ' . time();

        $this->visit('/companies')
            ->dontSee($companyName);

        $this->visit('/companies')
            ->dontSee('User is not authorised to Create Company.')
            ->see('Add Company') // button
            ->type($companyName, 'name')
            ->press('Add Company')
            ->see($companyName)
            ->seeInDatabase('companies', ['name' => $companyName]);
    }

    public function test_long_companies_cant_be_created()
    {
        $user = factory(User::class, 'owner')->create();
        $this->actingAs($user);
        
        $this->visit('/companies')
            ->type(str_random(300), 'name')
            ->press('Add Company')
            ->see('Whoops!');
    }
}
