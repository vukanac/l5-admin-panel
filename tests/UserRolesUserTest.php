<?php

namespace Test;

use App\User;
use App\Company;
use App\Role;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRolesUserTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Owner to create new users 
     */
    public function test_owner_can_create_user()
    {
        $owner = factory(User::class, 'owner')->create();
        $user = factory(User::class)->make();
            
        $this->actingAs($owner)
             ->visit('/users')
             ->dontSee('You are not authorised to Create User.')
             ->see('Add User');

    }

    public function test_viewer_cannot_create_user()
    {
        $viewer = factory(User::class, 'viewer')->create();
            
        $this->actingAs($viewer)
             ->visit('/users')
             ->see('You are not authorised to Create User.')
             ->dontSee('Add User');

    }



    public function test_owner_can_create_admin()
    {
        $user = factory(User::class, 'admin')->create();
        $this->actingAs($user)
            ->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User')
            ->see('Role');
    }

}