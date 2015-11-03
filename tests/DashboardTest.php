<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessOnlyIfLogged()
    {
        $this->visit('auth/logout')
             ->visit('dashboard')
             ->seePageIs('auth/login');
    }

    public function login()
    {
        $user = User::first();
        $this->be($user); //You are now authenticated
    }


    public function testDashboardMenuList()
    {
        $this->login();
        $this->visit('dashboard')
             ->seePageIs('dashboard')
             ->see('John');
    }
}
