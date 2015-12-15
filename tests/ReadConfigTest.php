<?php

namespace Test;

use App\User;
use App\Role;
use App\LicenceReminderCalculator;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadConfigTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_read_configuration_for_reminder_days()
    {
        // emulate config set
        $expected = [3, 7, 15];
        $expectedStr = implode(',', $expected);
        \Config::set('custom.remindOnDays', $expectedStr);

        // get config values
        $reminderCalculator = new LicenceReminderCalculator();
        $remindOnDays = $reminderCalculator->getReminderDays(); 

        // assert
        //
        // Expected to remind for Licence expiration
        // 3, 7, 15 days before expiration day.
        $this->assertEquals($expected, $remindOnDays);
    }
}
