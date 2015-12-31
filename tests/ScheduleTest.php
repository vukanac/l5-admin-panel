<?php

namespace Test;

use App\Company;
use App\Schedule;
use App\LicenceReminderCalculator;
use App\Repositories\ScheduleRepository;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScheduleTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_save_sample_schedule_in_db()
    {
        $schedule = factory(Schedule::class)->make();

        $schedule->save();

        $this->seeInDatabase('schedules', ['id' => $schedule->id]);
    }

    public function test_save_modified_schedule_in_db()
    {
        $companyOne = factory(Company::class)->create();
        // remove reminders for Company One
        $scheduleRepository = new ScheduleRepository();
        $scheduleRepository->removeAllForObject($companyOne);

        $schedule = new Schedule([
            'run_at' => '2015-03-15',
            'action' => ActionCommandSendReminderEmailCommand::class,
            'who_object' => Company::class,
            'who_id' => $companyOne->id,
            'parameters' => json_encode(array()),
            'status' => 'new'
        ]);
        
        $schedule->save();

        $this->seeInDatabase('schedules', ['run_at' => '2015-03-15']);
    }

    public function test_add_schedule_with_repository()
    {
        $company = factory(Company::class)->create();

        // remove reminders for Company One
        $scheduleRepository = new ScheduleRepository();
        $scheduleRepository->removeAllForObject($company);

        $runAt = '2015-03-18';
        $action = ActionCommandSendReminderEmailCommand::class;
        
        // add one action to schedule
        $schedule = $scheduleRepository->add($runAt, $action, $company, []);

        $this->seeInDatabase('schedules', ['run_at' => '2015-03-18', 'id' => $schedule->id]);
    }

    public function test_add_company_multiple_reminder_schedules_with_repository()
    {
        $expireAt =  '2016-03-30';
        $remindDays = [1,2,3];
        $remindAts = [1=>'2016-03-29',2=>'2016-03-28',3=>'2016-03-27'];

        //$this->assertInstanceOf('RuntimeException', new \Exception);

        $company = factory(Company::class)->create([
            'licence_expire_at' => $expireAt
            ]);

        if(!isset($company->licence_expire_at)) {
            throw new \Exception("Licence Expiration Date must be set first!");
        }

        // remove reminders for Company One
        $scheduleRepository = new ScheduleRepository();
        $scheduleRepository->removeAllForObject($company);

        // add multiple actions to schedule
        // dates when to send reminders
        $lrc = new LicenceReminderCalculator();
        $runAts = $lrc->getReminderDates($company->licence_expire_at, $remindDays);

        $this->assertEquals($remindAts, $runAts);

        $action = ActionCommandSendReminderEmailCommand::class;

        $schedules = [];
        foreach($runAts as $runAt) {
            $schedules[$runAt] = $scheduleRepository->add($runAt, $action, $company, []);
        }
        $this->assertCount(count($remindAts), $schedules);
        foreach($schedules as $runAt => $schedule) {
            $this->seeInDatabase('schedules', ['run_at' => $runAt, 'id' => $schedule->id]);
        }
    }


    public function test_add_company_multiple_reminder_schedules_with_add_command_in_repository()
    {
        // prepare
        $expireAt =  '2016-03-30';
        $remindDays = [1,5,20];
        $remindAts = [1=>'2016-03-29',5=>'2016-03-25',20=>'2016-03-10'];

        $company = factory(Company::class)->create([
            'licence_expire_at' => $expireAt
            ]);

        // ------------------------------------------
        // run
        $lrc = new LicenceReminderCalculator();
        $scheduleRepository = new ScheduleRepository();
        $schedules = $scheduleRepository->addSendReminderEmail($company, $lrc);
        // ------------------------------------------
        // assert
        foreach($schedules as $runAt => $schedule) {
            $this->seeInDatabase('schedules', ['run_at' => $runAt, 'id' => $schedule->id]);
        }
    }

    public function test_remove_all_schedule_for_one_company()
    {
        $companyOne = factory(Company::class)->create();
        $companyTwo = factory(Company::class)->create();
        $scheduleOne = factory(Schedule::class)->create([
            'who_object' => Company::class,
            'who_id' => $companyOne->id,
            ]);
        $scheduleTwo = factory(Schedule::class)->create([
            'who_object' => Company::class,
            'who_id' => $companyTwo->id,
            ]);

        // check is saved correctly to DB
        $this->seeInDatabase('schedules', [
            'id' => $scheduleOne->id,
            'who_object' => Company::class,
            'who_id' => $companyOne->id,
            ]);
        $this->seeInDatabase('schedules', [
            'id' => $scheduleTwo->id,
            'who_object' => Company::class,
            'who_id' => $companyTwo->id,
            ]);

        // remove reminders for Company One
        $scheduleRepository = new ScheduleRepository();
        $scheduleRepository->removeAllForObject($companyOne);

        // check is one removed from DB
        $this->dontSeeInDatabase('schedules', [
            'id' => $scheduleOne->id,
            'who_object' => Company::class,
            'who_id' => $companyOne->id,
            ]);
        $this->seeInDatabase('schedules', [
            'id' => $scheduleTwo->id,
            'who_object' => Company::class,
            'who_id' => $companyTwo->id,
            ]);
    }
}
