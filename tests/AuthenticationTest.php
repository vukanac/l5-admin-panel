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
        $name = 'John Doe X-'.$time;
        $email = 'john-'.$time.'@doex.com';
        $this->visit('/auth/logout')
        	 ->visit('/')
        	 ->see('Register')
        	 ->click('Register')
        	 ->seePageIs('auth/register')
        	 ->type($name, 'name')
        	 ->type($email, 'email')
        	 ->type('123456', 'password')
        	 ->type('123456', 'password_confirmation')
        	 ->press('Register')
        	 ->seePageIs('/dashboard')
             ->seeInDatabase('users', ['email' => $email]);

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
        	 ->type('john@doeZ.com', 'email')
        	 ->type('123456', 'password')
        	 ->press('Login')
        	 ->seePageIs('dashboard')
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
             ->see('Wrong pair username and password');
    }

    /**
     * Reset password
     *
     * @return void
     */
    public function testResetPasswordRegisterNewUser()
    {
        $time = time();
        $email = 'vladimir@vukanac.com';
        $this->visit('/auth/logout')
             ->visit('/')
             ->see('Register')
             ->click('Register')
             ->seePageIs('auth/register')
             ->type('John Doe A-'.$time, 'name')
             ->type($email, 'email')
             ->type('123456', 'password')
             ->type('123456', 'password_confirmation')
             ->press('Register')
             ->seePageIs('/dashboard');

        $this->visit('/auth/logout');
    }

    public function testResetPasswordLinkExists()
    {   $this->visit('/auth/login')
             ->see('Forgot password?')
             ->click('Forgot password')
             ->seePageIs('password/email')
             ->type($email, 'email');
    }

    public function testResetPasswordWithToken()
    {
        // check email for reset token
        $token = ''; // get token from email
        $this->visit('/password/reset/'.$token)
             ->seePageIs('/password/reset/'.$token)
             ->type('123456', 'password')
             ->type('123456', 'password_confirmation')
             ->press('Reset');
    }

}
