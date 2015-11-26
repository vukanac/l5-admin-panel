<?php

namespace Tests;

use App\User;
use App\Role;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /*
    - everyone can see user details
    - owner can CRUD user
    - ADMIN: can  perform all action in the account. Also create
             new users with different level of access
    - MANAGER: can perform all action in the account, except insert
             and suspend a company.
    - VIEWER: can view. 
    */

    public function test_user_option_is_in_menu()
    {
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->visit('/')
             ->see('menu.users')
             ->see('Users');
    }

    public function test_user_can_see_list_of_users()
    {
        $userOne = factory(User::class, 'admin')->create();
        $userTwo = factory(User::class, 'admin')->create();
        $userThree = factory(User::class, 'admin')->create();

        $this->actingAs($userTwo)
             ->visit('/users')
             ->see($userOne->name)
             ->see($userTwo->name)
             ->see($userThree->name);
    }

    public function test_every_user_can_get_other_user_details()
    {
        $owner = factory(User::class, 'owner')->create();
        $roles = Role::getAllRoles();

        foreach($roles as $role) {
            $user = factory(User::class, $role)->create();
            
            $this->actingAs($user)
                 ->get('/user/'.$owner->id)
                 ->assertResponseStatus(200);
        }
        
    }

    public function test_user_can_see_profile()
    {
        $user = factory(User::class, 'admin')->create();
        
        $this->actingAs($user)
             ->visit('/user/'.$user->id)
             ->see($user->name);
        
    }

    public function test_user_can_see_other_user_details()
    {
        $owner = factory(User::class, 'owner')->create();
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($user)
             ->visit('/user/'.$owner->id)
             ->see($owner->name);
        
    }

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

}
