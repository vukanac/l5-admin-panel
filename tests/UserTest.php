<?php

use App\User;
use App\Company;
use App\Role;

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
}
