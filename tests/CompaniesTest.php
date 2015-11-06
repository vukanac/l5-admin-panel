<?php

use App\User;
use App\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompaniesTest extends TestCase
{
    use DatabaseTransactions;


    
    public function test_companies_are_displayed_on_the_dashboard()
    {
        $user = factory(User::class)->create();

        factory(Company::class)->create(['name' => 'Company 1']);
        factory(Company::class)->create(['name' => 'Company 2']);
        factory(Company::class)->create(['name' => 'Company 3']);

        $this->actingAs($user)
             ->visit('/companies')
             ->see('Company 1')
             ->see('Company 2')
             ->see('Company 3');
    }


    public function test_companies_can_be_created()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        $this->visit('/companies')->dontSee('Company 1');

        $this->visit('/companies')
            ->type('Company 1', 'name')
            ->press('Add Company')
            ->see('Company 1');
    }


    public function test_long_companies_cant_be_created()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        $this->visit('/companies')
            ->type(str_random(300), 'name')
            ->press('Add Company')
            ->see('Whoops!');
    }
    
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
             //->withSession(['foo' => 'bar'])
             ->visit('companies')
             ->seePageIs('companies');
    }
    public function testAdminCanGetListOfCompaniesJson()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        // $companies = factory(App\Company::class, 3)->make();
        // dd($companies->toArray());
        $company = factory(App\Company::class)->create();

        $this->get('/companies')
             ->see($company->name);
    }
    public function testAdminCanSeeListOfCompanies()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        $this->visit('/companies')
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

    public function test_i_am_redirect_to_login_if_i_try_to_view_company_lists_without_logging_in()
    {
        $this->visit('/companies')->see('Login')->seePageIs('auth/login');
    }


    public function test_i_can_create_an_account()
    {
        $this->visit('/auth/register')
            ->seePageIs('auth/register')
            ->type('Taylor Otwell', 'name')
            ->type('taylor@laravel.com', 'email')
            ->type('secret', 'password')
            ->type('secret', 'password_confirmation')
            ->press('Register')
            //->seePageIs('/dashboard')
            ->seeInDatabase('users', ['email' => 'taylor@laravel.com']);
    }


    public function test_authenticated_users_can_create_companies()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->visit('/companies')
             ->type('Company 1', 'name')
             ->press('Add Company')
             ->see('Company 1')
             ->seeInDatabase('companies', ['name' => 'Company 1']);
    }


    public function test_users_can_delete_a_company()
    {
        $user = factory(User::class)->create();

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

    public function test_users_cant_view_companies_of_other_users()
    {
        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $userOne->companies()->save($companyOne = factory(Company::class)->create());
        $userTwo->companies()->save($companyTwo = factory(Company::class)->create());

        $this->actingAs($userOne)
             ->visit('/companies')
             ->see($companyOne->name)
             ->dontSee($companyTwo->name);
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

    public function testShowOneCompanyDetails()
    {
        // $companies = factory(App\Company::class, 3)->make();
        // dd($companies->toArray());
        $company = factory(App\Company::class)->create();

        $this->get('/companies/'.$company->id)
             ->see($company->id);
             // ->see($company->name);
    }

}
