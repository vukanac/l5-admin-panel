<?php

namespace Test;

use App\Company;
use App\Schedule;
use App\Repositories\ScheduleRepository;

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
            'action' => SendApprovalEmail::class,
            'who_object' => Company::class,
            'who_id' => $companyOne->id,
            'parameters' => json_encode(array()),
            'status' => 'new'
        ]);
        
        $schedule->save();

        $this->seeInDatabase('schedules', ['run_at' => '2015-03-15']);
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
