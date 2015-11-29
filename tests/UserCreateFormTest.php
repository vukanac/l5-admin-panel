<?php

namespace Test;

use App\User;
use App\Company;
use App\Role;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCreateFormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_be_created_from_form()
    {
        $owner = factory(User::class, 'owner')->create();
        $this->actingAs($owner);
        
        $user = factory(User::class, 'admin')->create();

        $this->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User') // button
            ->type($user->name, 'name')
            ->type($user->email, 'email')
            ->type($user->password, 'password')
            ->type($user->password, 'password_confirmation')
            ->select($user->role, 'role')
            ->press('Add User')
            ->see($user->name)
            ->seeInDatabase('users', ['email' => $user->email]);
    }

}
