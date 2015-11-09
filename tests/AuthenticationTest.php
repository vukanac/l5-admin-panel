<?php

use App\User;
use App\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;


    private $onRegisterPage = '/companies';


    public function test_i_can_create_an_account()
    {
        $email = time() . '-taylor@laravel.com';
        $this->visit('/auth/register')
            ->seePageIs('auth/register')
            ->type('Taylor Otwell', 'name')
            ->type($email, 'email')
            ->type('secret', 'password')
            ->type('secret', 'password_confirmation')
            ->press('Register')
            ->seePageIs($this->onRegisterPage)
            ->seeInDatabase('users', ['email' => 'taylor@laravel.com']);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginLogout()
    {
        $this->seeInDatabase('users', [
            'email' => 'vukanac@gmail.com',
            //'password' => bcrypt('123456')
            ]);

        $this->visit('/auth/login')
             ->seePageIs('/auth/login')
             ->type('vukanac@gmail.com', 'email')
             ->type('123456', 'password')
             ->press('Login')
             ->seePageIs('/companies')
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
