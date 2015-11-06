<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginLogout()
    {
        $user = factory(App\User::class)->create();

        $this->visit('auth/login')
             ->seePageIs('auth/login')
             ->type($user->email, 'email')
             ->type($user->password, 'password')
             ->press('Login')
             ->seePageIs('companies')
             ->dontSee('Login')
             ->dontSee('Register')
             ->see('Logout')
             ->click('Logout')
             ->seePageIs('/');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginFailed()
    {
        $this->visit('auth/login')
             ->type('UNKNOWN@doex.com', 'email')
             ->type('WRONG', 'password')
             ->press('Login')
             ->seePageIs('/auth/login')
             ->see('These credentials do not match our records.');
    }

    /**
     * Reset password
     *
     * @return void
     */
    public function testResetPassword()
    {
  
    }

    public function testResetPasswordLinkExists()
    {
        
    }

    public function testResetPasswordWithToken()
    {
        
    }

}
