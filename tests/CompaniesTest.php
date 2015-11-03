<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompaniesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRedirectIfNotLogged()
    {
    	// accessible only for logged user
        $this->visit('companies')
        	 ->seePageIs('auth/login');
    }

    public function testAccessIfLogged()
    {
    	$user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->withSession(['foo' => 'bar'])
             ->visit('companies')
    		 ->seePageIs('companies');
    }
    public function testAdminCanSeeListOfCompanies()
    {
    	Cache::shouldReceive('get')
                    ->once()
                    ->with('key')
                    ->andReturn('value');

        $this->visit('/companies')->see('value');


    	$this->visit('companies')
    	     ->seePageIs('companies')
    		 ->see('List of comapnies');
    }
}
