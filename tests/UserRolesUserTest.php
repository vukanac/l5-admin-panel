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
    public function test_owner_can_create_admin_but_not_owner()
    {
        $owner = factory(User::class, 'owner')->create();
        $admin = factory(User::class, 'admin')->make();
        $this->actingAs($owner)
            ->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User')
            ->see('name="role"')
            ->see('value="admin"')
            ->dontSee('value="owner"');
    }

    // public function test_there_is_only_one_owner()
    // {
    //     $owner = factory(User::class, 'owner')->make();
    //     $user = factory(User::class, 'owner')->create();
    //     \Session::start();
    //     $response = $this->call('POST', '/user', [
    //         'name' => $owner->name,
    //         '_token' => csrf_token()
    //         ]);
    //     $this->assertResponseOk();
    //     $this->assertEquals(200, $response->status());
    // }

    public function test_admin_can_create_user_with_role_except_owner()
    {
        $user = factory(User::class, 'admin')->create();
        $this->actingAs($user)
            ->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User')
            ->see('name="role"')
            ->see('value="admin"')
            ->dontSee('value="owner"');
    }

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
