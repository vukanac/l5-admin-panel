<?php

namespace Test;

use App\User;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MainMenuTest extends TestCase
{

    public function test_Register_option_is_in_menu()
    {
        $this->visit('/')
             ->see('menu.register')
             ->see('Register');
    }

    public function test_Login_option_is_in_menu()
    {
        $this->visit('/')
             ->see('menu.login')
             ->see('Login');
    }

    public function test_Logout_option_is_in_menu()
    {
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->visit('/')
             ->see('menu.logout')
             ->see('Logout');
    }
    
    public function test_company_option_is_in_menu()
    {
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->visit('/')
             ->see('menu.companies')
             ->see('Company');
    }

    public function test_user_option_is_in_menu()
    {
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->visit('/')
             ->see('menu.users')
             ->see('Users');
    }

    public function test_user_profile_option_is_in_menu()
    {
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->visit('/')
             ->see('menu.profile')
             ->see('Profile');
    }

}
