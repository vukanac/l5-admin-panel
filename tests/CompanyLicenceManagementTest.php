<?php

namespace Test;

use App\User;
use App\Company;
use App\Repositories\ScheduleRepository;
use App\Model\ActionQueue\ActionCommandSuspendCompanyCommand;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;
use App\Model\ActionQueue\ActionCommandSendSuspensionEmailCommand;

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

        // emulate config set
        $expected = [3, 15];
        $expectedStr = implode(',', $expected);
        \Config::set('custom.remindOnDays', $expectedStr);

        $user = factory(User::class, 'admin')->create();
        $company = factory(Company::class)->create();

        $today = Carbon::today();
        $expirationDate = $today->addYear();
        $expirationDateStr = $expirationDate->toDateString();

        $this->seeInDatabase('companies', [
                'id' => $company->id,
                'name' => $company->name,
                'licence_expire_at' => null,
                ])
             ->actingAs($user)
             ->visit('/company/'.$company->id.'/edit')
             ->see($company->name)
             ->see('name="licence_expire_at"')
             ->type($expirationDateStr, 'licence_expire_at')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'licence_expire_at' => $expirationDateStr,
                'is_suspended' => false,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => $expirationDate->copy()->subDay(3)->toDateString(),
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => $expirationDate->copy()->subDay(15)->toDateString(),
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => $expirationDateStr,
                'action' => ActionCommandSuspendCompanyCommand::class,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => $expirationDateStr,
                'action' => ActionCommandSendSuspensionEmailCommand::class,
                ])
             ;
    }

    /**
     * On specific day reminder action should be listed
     */
    public function test_list_reminder_on_day()
    {
        // emulate config set
        $expected = [1, 2];
        $expectedStr = implode(',', $expected);
        \Config::set('custom.remindOnDays', $expectedStr);

        $user = factory(User::class, 'admin')->create();
        $companyOne = factory(Company::class)->create();
        $companyTwo = factory(Company::class)->create();

        $this->seeInDatabase('companies', [
                'id' => $companyOne->id,
                'name' => $companyOne->name,
                'licence_expire_at' => null,
                ])
             ->seeInDatabase('companies', [
                'id' => $companyTwo->id,
                'name' => $companyTwo->name,
                'licence_expire_at' => null,
                ])
             ->actingAs($user)
             ->visit('/company/'.$companyOne->id.'/edit')
             ->see($companyOne->name)
             ->see('name="licence_expire_at"')
             ->type('2016-04-30', 'licence_expire_at')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $companyOne->id,
                'licence_expire_at' => '2016-04-30',
                'is_suspended' => false,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $companyOne->id,
                'run_at' => '2016-04-29',
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $companyOne->id,
                'run_at' => '2016-04-28',
                'action' => ActionCommandSendReminderEmailCommand::class,
                ]);

        $this->visit('/company/'.$companyTwo->id.'/edit')
             ->see($companyTwo->name)
             ->see('name="licence_expire_at"')
             ->type('2016-04-30', 'licence_expire_at')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $companyTwo->id,
                'licence_expire_at' => '2016-04-30',
                'is_suspended' => false,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $companyTwo->id,
                'run_at' => '2016-04-29',
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $companyTwo->id,
                'run_at' => '2016-04-28',
                'action' => ActionCommandSendReminderEmailCommand::class,
                ])
             ;
        $repository = new ScheduleRepository();

        $date = new Carbon('2016-04-28');
        $list = $repository->getActionsForDate($date);
        $this->assertCount(2, $list);
        $list = $repository->getNewActionsForDate($date);
        $this->assertCount(2, $list);
    }

    public function test_suspend_company_with_command_on_expiration_date()
    {
        $expirationDate = new Carbon('2017-06-30');
        $company = factory(Company::class)->create();
        $user = factory(User::class, 'admin')->create();


        $this->actingAs($user)
             ->visit('/company/'.$company->id.'/edit')
             ->see($company->name)
             ->see('name="licence_expire_at"')
             ->type($expirationDate->toDateString(), 'licence_expire_at')
             ->press('Save Edit')
             ->seeInDatabase('companies', [
                'id' => $company->id,
                'licence_expire_at' => $expirationDate->toDateString(),
                'is_suspended' => false,
                ])
             ->seeInDatabase('schedules', [
                'who_object' => Company::class,
                'who_id' => $company->id,
                'run_at' => $expirationDate->toDateString(),
                'action' => ActionCommandSuspendCompanyCommand::class,
                ])
             ;

        // enter to testing time - set date to be expiration date
        Carbon::setTestNow($expirationDate);

        $company = Company::findOrFail($company->id);
        $this->assertEquals(false, $company->is_suspended);
        
        $results = \App\Model\ActionQueue\ActionCommandScheduled::run();

        $company = Company::findOrFail($company->id);
        $this->assertEquals(true, $company->is_suspended);

        // exit from testing time - reset current time
        Carbon::setTestNow();
    }
//
}
