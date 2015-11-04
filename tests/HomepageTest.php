<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomepageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomepageIsLoginForm()
    {
        $this->visit('auth/logout')
        	 ->visit('/')
        	 ->seePageIs('auth/login');
    }
}