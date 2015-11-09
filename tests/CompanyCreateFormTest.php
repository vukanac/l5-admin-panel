<?php

use App\User;
use App\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyCreateFormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_companies_can_be_created()
    {
        $user = factory(User::class)->create([
            'name' => 'Vladimir Vukanac'
            ]);
        $this->actingAs($user);
        
        $this->visit('/companies')->dontSee('Company 1');

        $this->visit('/companies')
            ->type('Company 1', 'name')
            ->press('Add Company')
            ->see('Company 1');
    }


    public function test_long_companies_cant_be_created()
    {
        $user = factory(User::class)->create([
            'name' => 'Vladimir Vukanac'
            ]);
        $this->actingAs($user);
        
        $this->visit('/companies')
            ->type(str_random(300), 'name')
            ->press('Add Company')
            ->see('Whoops!');
    }

    public function test_companies_can_be_created_by_admin()
    {
        $user = factory(User::class)->create([
        	'name' => 'Vladimir Vukanac'
        	]);
        $this->actingAs($user);
        
        $this->visit('/companies')
        	->dontSee('Company 1');

        $this->visit('/companies')
            ->type('Company 1', 'name')
            ->press('Add Company')
            ->see('Company 1')
            ->seeInDatabase('companies', ['name' => 'Company 1']);
    }

    public function test_companies_cannot_be_created_by_visitor()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        $this->visit('/companies')->dontSee('Company 1');

        $this->visit('/companies')
        	->dontSee('Add Company');
            // ->type('Company 1', 'name')
            // ->press('Add Company')
            // ->see('Company 1');
    }

}
