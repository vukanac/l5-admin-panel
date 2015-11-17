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
        $user = factory(User::class, 'admin')->make();

        $this->visit('/auth/register')
            ->seePageIs('auth/register')
            ->type($user->name, 'name')
            ->type($user->email, 'email')
            ->type($user->password, 'password')
            ->type($user->password, 'password_confirmation')
            ->press('Register')
            ->seePageIs($this->onRegisterPage)
            ->seeInDatabase('users', ['email' => $user->email]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginLogout()
    {
        $origPass = '321654';
        $user = factory(User::class, 'admin')->create(
            ['password' => bcrypt($origPass)]
            );
        
        $this->seeInDatabase('users', [
            'email' => $user->email,
            ]);

        $this->visit('/auth/login')
             ->seePageIs('/auth/login')
             ->type($user->email, 'email')
             ->type($origPass, 'password')
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

    // /**
    //  * Reset password
    //  *
    //  * @return void
    //  */
    // public function testResetPassword()
    // {
  
    // }

    // public function testResetPasswordLinkExists()
    // {
        
    // }

    // public function testResetPasswordWithToken()
    // {
        
    // }

}
