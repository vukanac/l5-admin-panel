<?php

namespace Test;

use App\User;

use TestCase;
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
    public function test_i_can_login_and_logout()
    {
        $origPass = '321654';
        $user = factory(User::class, 'admin')->create([
            'password' => bcrypt($origPass)
            ]);
        
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
    public function test_i_cannot_login_with_wrong_username_and_password_LoginFailed()
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
    public function test_i_can_see_forgot_password_link_and_reset_password_email_form()
    {
        $user = factory(User::class, 'admin')->make();

        // see password reset form
        $this->visit('/auth/login')
             ->see('Forgot password?')
             ->click('Forgot password?')
             ->seePageIs('/password/email')
             ->see('name="email"')
             ->see('Send Password Reset Link')
             ->type($user->email, 'email')
             // ->press('Send Password Reset Link')
             // ->see('We have e-mailed your password reset link!');
             ;
    }

    public function testResetPasswordWrongEmail()
    {
        $user = factory(User::class, 'admin')->make();

        // see password reset form
        $this->visit('/auth/login')
             ->dontSeeInDatabase('users', ['email' => $user->email])
             ->see('Forgot password?')
             ->click('Forgot password?')
             ->seePageIs('/password/email')
             ->see('name="email"')
             ->see('Send Password Reset Link')
             ->type($user->email, 'email')
             ->press('Send Password Reset Link')
             ->see('Whoops!')
             ->see('We can\'t find a user with that e-mail address.');
    }



    public function test_i_can_see_reset_password_with_emailed_token()
    {
        $user = factory(User::class, 'admin')->create();
        $token = 'TOKENfromEMAIL';

        $this->visit('/password/reset/'.$token)
             ->see('name="email"')
             ->see('name="password"')
             ->see('name="password_confirmation"')
             ->see('Reset Password')
             ->type($user->email, 'email')
             ->type($user->password, 'password')
             ->type($user->password, 'password_confirmation')
             ->press('Reset Password')
             ->see('This password reset token is invalid.');
    }

    public function test_i_cannot_reset_password_without_confirmation_password()
    {
        $user = factory(User::class, 'admin')->create();
        $token = 'TOKENfromEMAIL';

        $this->visit('/password/reset/'.$token)
             ->see('name="email"')
             ->see('name="password"')
             ->see('name="password_confirmation"')
             ->see('Reset Password')
             ->type($user->email, 'email')
             ->type($user->password, 'password')
             ->type($user->password . '-XXXXXX', 'password_confirmation')
             ->press('Reset Password')
             ->see('The password confirmation does not match');
    }

    // public function testResetPasswordWithToken()
    // {
        
    // }

    public function test_i_am_redirect_to_login_if_i_try_to_view_companies_list_without_logging_in()
    {
        // accessible only for logged user
        $this->visit('/companies')
             ->seePageIs('auth/login');
    }

    public function test_i_am_redirect_to_login_if_i_try_to_view_users_list_without_logging_in()
    {
        // accessible only for logged user
        $this->visit('/users')
             ->seePageIs('auth/login');
    }

}
