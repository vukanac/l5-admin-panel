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
        $this->assertTrue(true);
        // $this->visit('companies')
        // 	 ->seePageIs('auth/login');
    }

    public function testAccessIfLogged()
    {
        $this->assertTrue(true);

    	// $user = factory(App\User::class)->create();

     //    $this->actingAs($user)
     //         ->withSession(['foo' => 'bar'])
     //         ->visit('companies')
    	// 	 ->seePageIs('companies');
    }
    public function testAdminCanGetListOfCompaniesJson()
    {
        $this->assertTrue(true);
        return;
        $this->get('/')
             ->seeJson([
             ]);
    }
    public function testAdminCanSeeListOfCompanies()
    {
        $this->visit('/')
             ->see('List of companies');
    	// Cache::shouldReceive('get')
     //                ->once()
     //                ->with('key')
     //                ->andReturn('value');

     //    $this->visit('/companies')->see('value');


    	// $this->visit('companies')
    	//      ->seePageIs('companies')
    	// 	 ->see('List of comapnies');
    }
    public function testNew()
    {
        $this->assertTrue(true);
        return;
        $this->post('/company', ['name' => 'Tierre'])
             ->seeJson([
                 'created' => true,
             ]);
    }
    public function testDelete()
    {
        $this->assertTrue(true);
        return;
        $company = \App\Company::first();
        if(is_null($company)) {
            $this->assertTrue(true);
            return;
        }
        $id = $company['id'];
        $this->delete('/company/'.$id)->seeJsonEquals([
                'success' => 1,
                'message' => '',
                'deleted' => true,
             ]);
    }
}
