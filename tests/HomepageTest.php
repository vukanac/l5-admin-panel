<?php

namespace Test;

use App\User;
use App\Company;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomepageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testWelcome()
    {
        $this->visit('auth/logout')
             ->visit('/')
             ->see('Welcome')
             ->see('Please login with provided credentials.')
             ->see('Login')
             ->see('Register')
             ->dontSee('Logout');
    }

    public function testErrorPageNotFoundStatusCode()
    {
        $this->get('/x')
             ->see('errors.not-found')
             ->see('Not found.')
             ->assertResponseStatus(404);
    }

    public function testErrorPageNotAuthorisedStatusCode()
    {
        $this->expectOutputString(''); // tell PHPUnit to expect '' as output

        $user = factory(User::class, 'author')->create();
        $user->companies()->save($company = factory(Company::class)->create());

        $this->actingAs($user)
             ->get('/company/'.$company->id.'/edit')
             ->see('errors.unauthorised')
             ->see('Unauthorised.')
             ->assertResponseStatus(403);
    }
}
