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
    public function testRegister()
    {
    	$time = time();
        $this->visit('/auth/logout')
        	 ->visit('/')
        	 ->see('Register')
        	 ->click('Register')
        	 ->seePageIs('auth/register')
        	 ->type('John Doe X-'.$time, 'name')
        	 ->type('john-'.$time.'@doex.com', 'email')
        	 ->type('123456', 'password')
        	 ->type('123456', 'password_confirmation')
        	 ->press('Register')
        	 ->seePageIs('/dashboard');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginLogout()
    {
        $this->visit('auth/logout')
        	 ->seePageIs('/')
        	 ->visit('/')
        	 ->seePageIs('/')
        	 ->dontSee('Logout')
        	 ->see('Login')
        	 ->click('Login')
        	 ->seePageIs('auth/login')
        	 ->type('john@doex.com', 'email')
        	 ->type('123456', 'password')
        	 ->press('Login')
        	 ->seePageIs('/dashboard')
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
        	 ->seePageIs('/auth/login');
    }

}
