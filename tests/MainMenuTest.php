<?php

namespace Tests;

use App\User;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MainMenuTest extends TestCase
{
    
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

}
