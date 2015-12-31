<?php

namespace Test;

use App\User;
use App\Company;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;

use TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyLicenceManagementTest extends TestCase
{
    use DatabaseTransactions;

    
    public function test_set_and_get_licence_expiration_date()
    {
        $company = factory(Company::class)->create();
        $this->seeInDatabase('companies', ['id' => $company->id, 'licence_expire_at' => null]);

        $date = Carbon::now();
        $company->licence_expire_at = $date;
        $company->save();
        $this->seeInDatabase('companies', ['id' => $company->id, 'licence_expire_at' => $date->toDateString()]);
        $companyAgain = Company::find($company->id);
        $this->assertEquals($date->toDateString(), $companyAgain->licence_expire_at);
    }

    public function test_new_company_is_suspended()
    {
        $company = factory(Company::class)->create();
        $this->seeInDatabase('companies', ['id' => $company->id, 'is_suspended' => true]);

        $company->is_suspended = false;
        $company->save();

        $this->seeInDatabase('companies', ['id' => $company->id, 'is_suspended' => false]);

        $companyAgain = Company::find($company->id);
        $this->assertEquals(false, (boolean) $companyAgain->is_suspended);
    }

    public function test_edit_company_to_set_licence_expiration_date()
    {
        // Procedure:

        // A) SAVE NEW LICENCE

        // 1. Company buys licence
        // 2. Admin creates company
        // 3. Admin add licence expiration date
        // 4. Application change company status to NOT_SUSPENDED
        // 5. Application SendApprovalEmail
        // 6. Application calculate number and dates of reminders based on expiration date and reminder configuration
        // 7. Application add calculated remainders to reminder queue.

        //$this->withoutMiddleware();

        // emulate config set
        $expected = [3, 15];
        $expectedStr = implode(',', $expected);
        \Config::set('custom.remindOnDays', $expectedStr);

        $user = factory(User::class, 'admin')->create();
        $company = factory(Company::class)->create();

        $this->seeInDatabase('companies', [
                'id' => $company->id,
                'name' => $company->name,
                'licence_expire_at' => null,
                ])
             ->actingAs($user)
             ->visit('/company/'.$company->id.'/edit')
             ->see($company->name)
             ->see('name="licence_expire_at"')
             ->type('2015-04-30', 'licence_expire_at')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'licence_expire_at' => '2015-04-30',
                'is_suspended' => false,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => '2015-04-27',
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => '2015-04-15',
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ;
    }

//
}
