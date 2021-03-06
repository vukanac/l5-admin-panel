<?php

namespace Test;

use App\User;
use App\Company;
use App\Role;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRolesCompanyTest extends TestCase
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

    public function test_every_user_role_can_see_any_company_details()
    {
        // create owner with company
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $roles = Role::getAllSystemRoles();

        foreach($roles as $role) {
            $user = factory(User::class, $role)->create();
            $this->actingAs($user)
                 ->get('/company/'.$company->id)
                 ->assertResponseStatus(200);
        }
    }

    public function test_owner_can_create_edit_and_delete_company()
    {
        $owner = factory(User::class, 'owner')->create();

        $user = factory(User::class, 'admin')->create();
        $user->companies()->save($company = factory(Company::class)->create());

        $this->actingAs($owner)
             ->visit('/companies')
             ->dontSee('User is not authorised to Create Company.')
             ->see('Add Company')
             ->see($company->name)
             ->see('edit-company-'.$company->id)
             ->see('delete-company-'.$company->id);
    }


    public function test_admin_can_create_company()
    {
        $user = factory(User::class, 'admin')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->dontSee('User is not authorised to Create Company.')
             ->see('Add Company');
    }

    public function test_admin_can_edit_company()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'admin')->create();
        
        $this->actingAs($user)
             ->visit('/companies')
             ->see($company->name)
             ->see('edit-company-'.$company->id);
    }

    public function test_admin_can_delete_company()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'admin')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->see('delete-company-'.$company->id);
    }

    public function test_manager_cannot_create_company()
    {
        $user = factory(User::class, 'manager')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->see('User is not authorised to Create Company.')
             ->dontSee('Add Company');
    }

    public function test_manager_can_edit_company()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'manager')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->see('edit-company-'.$company->id);
    }

    public function test_manager_can_delete_company()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'manager')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->see('delete-company-'.$company->id);
    }

    // public function test_manager_cannot_suspend_company()
    // {
    //     // Stop here and mark this test as incomplete.
    //     $this->markTestIncomplete(
    //         'This test has not been implemented yet.'
    //     );

    //     $owner = factory(User::class, 'owner')->create();
    //     $owner->companies()->save($company = factory(Company::class)->create());

    //     $user = factory(User::class, 'manager')->create();
    //     $this->actingAs($user)
    //          ->visit('/company/'.$company->id.'/edit')
    //          ->dontSee('suspend-company-'.$company->id);
    // }

    public function test_author_cannot_create_company()
    {
        $user = factory(User::class, 'author')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->see('User is not authorised to Create Company.')
             ->dontSee('Add Company');
    }

    public function test_author_cannot_edit_company_dont_see_edit_company_button()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'author')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->dontSee('edit-company-'.$company->id);
    }

    public function test_author_cannot_delete_company()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'author')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->dontSee('delete-company-'.$company->id);
    }

    public function test_viewer_cannot_create_company()
    {
        $user = factory(User::class, 'viewer')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->see('User is not authorised to Create Company.')
             ->dontSee('Add Company');
    }

    public function test_viewer_cannot_edit_company_dont_see_edit_company_button()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'viewer')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->dontSee('edit-company-'.$company->id);
    }

    public function test_viewer_cannot_delete_company()
    {
        $owner = factory(User::class, 'owner')->create();
        $owner->companies()->save($company = factory(Company::class)->create());

        $user = factory(User::class, 'viewer')->create();
        $this->actingAs($user)
             ->visit('/companies')
             ->dontSee('delete-company-'.$company->id);
    }

}
