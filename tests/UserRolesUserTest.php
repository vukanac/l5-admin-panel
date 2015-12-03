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

    // public function test_owner_cannot_delete_owner()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $owner = factory(User::class, 'owner')->create();
    //     $user = factory(User::class, 'owner')->create();

    //     $this->actingAs($owner)
    //          ->visit('/users')
    //          ->seeInDatabase('users', ['email' => $user->email])
    //          ->dontSee('delete-user-'.$user->id);
    //     $this->delete('user/'.$user->id)
    //          ->seeInDatabase('users', ['email' => $user->email])
    //          ->assertResponseStatus(403);
    // }

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

    // public function test_admin_cannot_delete_owner()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $admin = factory(User::class, 'admin')->create();
    //     $user = factory(User::class, 'owner')->create();

    //     $this->actingAs($admin)
    //          ->visit('/users')
    //          ->seeInDatabase('users', ['email' => $user->email])
    //          ->dontSee('delete-user-'.$user->id);
    //     $this->delete('user/'.$user->id)
    //          ->seeInDatabase('users', ['email' => $user->email])
    //          ->assertResponseStatus(403);
    //     // // Stop here and mark this test as incomplete.
    //     // $this->markTestIncomplete(
    //     //     'This test has not been implemented yet.'
    //     // );
    // }

    public function test_admin_can_create_user_with_role_except_owner()
    {
        $admin = factory(User::class, 'admin')->create();
        $user = factory(User::class, 'admin')->make();

        $this->actingAs($admin)
            ->visit('/users')
            ->dontSee('User is not authorised to Create User.')
            ->see('Add User')
            ->see('name="role"')
            ->see('value="admin"')
            ->dontSee('value="owner"');

        $this->post('/user', $user->toArray())
             ->assertResponseStatus(200);
        $this->seeInDatabase('users', ['email' => $user->email]);
    }

    // public function test_admin_can_edit_user_and_change_user_role()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );

    //     $user = factory(User::class, 'admin')->create();
    //     $userTwo = factory(User::class, 'admin')->create();

    //     $oldName = $userTwo->name;
    //     $newName = 'Test name '.time();

    //     $this->actingAs($user)
    //         ->visit('/users')
    //         ->see('edit-user-'.$userTwo->id)
    //         ->visit('/user/'.$userTwo->id.'/edit')
    //         ->see('Add User')
    //         ->see('name="role"');
    // }

    // public function test_manager_cannot_create_user()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'manager')->create();
    //     $this->actingAs($user)
    //         ->visit('/users')
    //         ->see('User is not authorised to Create User.')
    //         ->dontSee('Add User');
    // }

    // public function test_manager_can_edit_user()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'admin')->create();
    //     $userTwo = factory(User::class, 'admin')->create();
    //     $this->actingAs($user)
    //          ->visit('/user/'.$userTwo->id.'/edit')
    //          ->assertResponseStatus(403);
    // }

    // public function test_manager_cannot_change_user_role()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'admin')->create();
    //     $userTwo = factory(User::class, 'admin')->create();
    //     $this->actingAs($user)
    //          ->visit('/user/'.$userTwo->id.'/edit')
    //          ->see('Add User')
    //          ->dontSee('Role');
    // }
    // public function test_author_cannot_create_user()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'manager')->create();
    //     $this->actingAs($user)
    //         ->visit('/users')
    //         ->see('User is not authorised to Create User.')
    //         ->dontSee('Add User');
    // }

    // public function test_author_cannot_edit_user()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'admin')->create();
    //     $userTwo = factory(User::class, 'admin')->create();
    //     $this->actingAs($user)
    //          ->visit('/user/'.$userTwo->id.'/edit')
    //          ->assertResponseStatus(403);
    // }

    // public function test_author_cannot_change_user_role()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'admin')->create();
    //     $userTwo = factory(User::class, 'admin')->create();
    //     $this->actingAs($user)
    //          ->visit('/user/'.$userTwo->id.'/edit')
    //          ->see('Add User')
    //          ->dontSee('Role');
    // }

    // public function test_viewer_cannot_create_user()
    // {
    //     $viewer = factory(User::class, 'viewer')->create();
            
    //     $this->actingAs($viewer)
    //          ->visit('/users')
    //          ->see('You are not authorised to Create User.')
    //          ->dontSee('Add User');
    // }

    // public function test_viewer_cannot_edit_user()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'viewer')->create();
    //     $userTwo = factory(User::class, 'admin')->create();
    //     $this->actingAs($user)
    //          ->visit('/user/'.$userTwo->id.'/edit')
    //          ->assertResponseStatus(403);
    // }

    // public function test_viewer_cannot_change_user_role()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );
    //     $user = factory(User::class, 'viewer')->create();
    //     $userTwo = factory(User::class, 'admin')->create();
    //     $this->actingAs($user)
    //          ->visit('/user/'.$userTwo->id.'/edit')
    //          ->see('Add User')
    //          ->dontSee('Role');
    // }
}
