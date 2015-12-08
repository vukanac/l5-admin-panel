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
    public function test_owner_can_create_user_but_not_owner()
    {
        $owner = factory(User::class, 'owner')->create();
        $user = factory(User::class, 'owner')->make();
        $this->actingAs($owner)
            ->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User')
            ->see('name="role"')
            ->see('value="admin"')
            ->see('value="manager"')
            ->see('value="author"')
            ->see('value="viewer"')
            ->dontSee('value="owner"');
    }

    // public function test_owner_must_be_only_one()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );

    //     // $user = factory(User::class, 'owner')->create();
    //     // $owner = factory(User::class, 'owner')->make();
    //     // \Session::start();
    //     // $this->actingAs($user)
    //     // $response = $this->post('/user', [
    //     //     'name' => $owner->name,
    //     //     'email' => $owner->email,
    //     //     'password' => $owner->password,
    //     //     'password_confirmation' => $owner->password,
    //     //     'role' => 'owner',
    //     //     '_token' => csrf_token()
    //     //     ]);
    //     // $this->assertResponseOk();
    //     // $this->assertEquals(200, $response->status());
    // }

    public function test_owner_can_delete_user()
    {
        $owner = factory(User::class, 'owner')->create();
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($owner)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->see('delete-user-'.$user->id)
             ->press('delete-user-'.$user->id)
             ->dontSeeInDatabase('users', ['email' => $user->email])
             ->seePageIs('/users');
        // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //     'This test has not been implemented yet.'
        // );
    }

    public function test_owner_cannot_delete_owner()
    {
        $owner = factory(User::class, 'owner')->create();
        $user = factory(User::class, 'owner')->create();

        $this->actingAs($owner)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->dontSee('delete-user-'.$user->id);
    }

    // public function test_owner_cannot_delete_self()
    // {
    //     // prevent self deleting!!!

    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'owner')->create();

    //     $this->actingAs($user)
    //          ->visit('/users')
    //          ->seeInDatabase('users', ['email' => $user->email])
    //          ->dontSee('delete-user-'.$user->id);
    //     // $this->delete('user')
    //     //      ->press('delete-user-'.$user->id)
    //     //      ->dontSeeInDatabase('users', ['email' => $user->email])
    //     //      ->seePageIs('/users');
    //     $this->delete('user/'.$user->id)
    //          //->seeInDatabase('users', ['email' => $user->email])
    //          ->assertResponseStatus(403);
    // }

    // public function test_user_cannot_delete_self()
    // {
    //     // prevent self deleting!!!

    //     // // Stop here and mark this test as incomplete.
    //     // $this->markTestIncomplete(
    //     //     'This test has not been implemented yet.'
    //     // );
    //     $user = factory(User::class, 'admin')->create();

    //     $this->actingAs($user)
    //          ->visit('/users')
    //          ->seeInDatabase('users', ['email' => $user->email])
    //          ->dontSee('delete-user-'.$user->id);
    //     // $this->delete('user')
    //     //      ->press('delete-user-'.$user->id)
    //     //      ->dontSeeInDatabase('users', ['email' => $user->email])
    //     //      ->seePageIs('/users');
    //     $this->delete('user/'.$user->id)
    //          //->seeInDatabase('users', ['email' => $user->email])
    //          ->assertResponseStatus(403);
    // }

    public function test_owner_can_edit_user_and_change_user_role_except_owner()
    {
        
        $owner = factory(User::class, 'owner')->create();
        $userOld = factory(User::class, 'admin')->create();
        $userNew = factory(User::class, 'author')->make();

        $this->actingAs($owner)
            ->seeInDatabase('users', ['id' => $userOld->id, 'name' => $userOld->name, 'role' => $userOld->role])
            ->visit('/users')
            ->see('edit-user-'.$userOld->id)
            ->click('edit-user-'.$userOld->id)
            ->seePageIs('/user/'.$userOld->id.'/edit')
            ->see($userOld->name)
            ->see('name="role"')
            ->see('value="admin"')
            ->see('value="manager"')
            ->see('value="author"')
            ->see('value="viewer"')
            ->dontSee('value="owner"')
            ->see('Save User Changes')
            ->type($userNew->name, 'name')
            ->select($userNew->role, 'role')
            ->press('Save User Changes')
            ->seeInDatabase('users', ['id' => $userOld->id, 'name' => $userNew->name, 'role' => $userNew->role])
            ->seePageIs('/users')
            ->see($userNew->name);
    }

    public function test_owner_cannot_change_his_own_role()
    {
        $owner = factory(User::class, 'owner')->create();
        
        $this->actingAs($owner)
            ->visit('/user/'.$owner->id.'/edit')
            ->see($owner->name)
            ->see($owner->id)
            ->see($owner->email)
            ->see('Edit User')
            ->dontSee('name="role"');
    }

    public function test_admin_can_create_user_with_role_except_owner()
    {
        $admin = factory(User::class, 'admin')->create();

        $this->actingAs($admin)
            ->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User')
            ->see('name="role"')
            ->see('value="admin"')
            ->dontSee('value="owner"');
    }

    public function test_admin_can_edit_user_and_change_user_role()
    {
        $admin = factory(User::class, 'admin')->create();
        $userOld = factory(User::class, 'admin')->create();
        $userNew = factory(User::class, 'author')->make();

        $this->actingAs($admin)
            ->seeInDatabase('users', ['id' => $userOld->id, 'name' => $userOld->name, 'role' => $userOld->role])
            ->visit('/users')
            ->see('edit-user-'.$userOld->id)
            ->click('edit-user-'.$userOld->id)
            ->seePageIs('/user/'.$userOld->id.'/edit')
            ->see($userOld->name)
            ->see('name="role"')
            ->see('Save User Changes')
            ->type($userNew->name, 'name')
            ->select($userNew->role, 'role')
            ->press('Save User Changes')
            ->seeInDatabase('users', ['id' => $userOld->id, 'name' => $userNew->name, 'role' => $userNew->role])
            ->seePageIs('/users')
            ->see($userNew->name);
    }

    public function test_admin_cannot_edit_owner()
    {
        $admin = factory(User::class, 'admin')->create();
        $user = factory(User::class, 'owner')->create();

        $this->actingAs($admin)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->dontSee('edit-user-'.$user->id);
    }

    public function test_admin_can_delete_user()
    {
        $admin = factory(User::class, 'admin')->create();
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($admin)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->see('delete-user-'.$user->id)
             ->press('delete-user-'.$user->id)
             ->dontSeeInDatabase('users', ['email' => $user->email]);
    }

    public function test_admin_cannot_delete_owner()
    {
        $admin = factory(User::class, 'admin')->create();
        $user = factory(User::class, 'owner')->create();

        $this->actingAs($admin)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->dontSee('delete-user-'.$user->id);
    }

    public function test_manager_cannot_create_user()
    {
        $user = factory(User::class, 'manager')->create();
        $this->actingAs($user)
            ->visit('/users')
            ->see('You are not authorised to Create User.')
            ->dontSee('Add User');
    }

    public function test_manager_can_edit_user()
    {
        $user = factory(User::class, 'manager')->create();
        $userTwo = factory(User::class, 'admin')->create();
        $this->actingAs($user)
             ->visit('/user/'.$userTwo->id.'/edit')
             ->assertResponseStatus(200);

        $this->actingAs($user)
             ->visit('/users')
             ->see('edit-user-'.$userTwo->id)
             ->click('edit-user-'.$userTwo->id)
             ->seePageIs('/user/'.$userTwo->id.'/edit');
    }

    public function test_manager_cannot_change_user_role()
    {
        $user = factory(User::class, 'manager')->create();
        $userTwo = factory(User::class, 'admin')->create();
        $this->actingAs($user)
             ->visit('/user/'.$userTwo->id.'/edit')
             ->see('Add User')
             ->dontSee('name="role"');
    }

    public function test_manager_cannot_edit_owner()
    {
        $user = factory(User::class, 'manager')->create();
        $userTwo = factory(User::class, 'owner')->create();
        $this->actingAs($user)
             ->get('/user/'.$userTwo->id.'/edit')
             ->assertResponseStatus(403);
    }

    public function test_manager_cannot_delete_user()
    {
        $manager = factory(User::class, 'manager')->create();
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($manager)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->dontSee('delete-user-'.$user->id);
    }

    public function test_author_cannot_create_user()
    {
        $user = factory(User::class, 'author')->create();
        $this->actingAs($user)
            ->visit('/users')
            ->see('You are not authorised to Create User.')
            ->dontSee('Add User');
    }

    public function test_author_cannot_edit_user()
    {
        $user = factory(User::class, 'author')->create();
        $userTwo = factory(User::class, 'admin')->create();
        $this->actingAs($user)
             ->get('/user/'.$userTwo->id.'/edit')
             ->assertResponseStatus(403);
    }

    public function test_author_cannot_delete_user()
    {
        $author = factory(User::class, 'author')->create();
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($author)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->dontSee('delete-user-'.$user->id);
    }

    public function test_viewer_cannot_create_user()
    {
        $viewer = factory(User::class, 'viewer')->create();
            
        $this->actingAs($viewer)
             ->visit('/users')
             ->see('You are not authorised to Create User.')
             ->dontSee('Add User');
    }

    public function test_viewer_cannot_edit_user()
    {
        $user = factory(User::class, 'viewer')->create();
        $userTwo = factory(User::class, 'admin')->create();
        $this->actingAs($user)
             ->get('/user/'.$userTwo->id.'/edit')
             ->assertResponseStatus(403);
    }

    public function test_viewer_can_edit_self()
    {
        $user = factory(User::class, 'viewer')->create();
        $this->actingAs($user)
             ->visit('/users')
             ->see('edit-user-'.$user->id);
    }

    public function test_viewer_cannot_delete_user()
    {
        $author = factory(User::class, 'viewer')->create();
        $user = factory(User::class, 'admin')->create();

        $this->actingAs($author)
             ->visit('/users')
             ->seeInDatabase('users', ['email' => $user->email])
             ->dontSee('delete-user-'.$user->id);
    }

}
