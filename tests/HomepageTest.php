<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomepageTest extends \TestCase
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
}
