<?php

namespace Test;

use App\User;
use App\Role;
use App\LicenceReminderCalculator;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LicenceReminderCalculatorTest extends TestCase
{
    /**
     * Generated from @assert (true, 'logs/') == false.
     *
     * @covers App\LicenceReminderCalculator::getReminderDates
     */
    public function testGetReminderDates()
    {
    	$expirationDate = '2016-01-30';
    	$reminderDays = [5, 10, 20];
    	$expectedArr = [
    		5 => '2016-01-25',
    		10 => '2016-01-20',
    		20 => '2016-01-10',
    		];

    	$lrc = new LicenceReminderCalculator();

        $this->assertEquals(
                $expectedArr
                , $lrc->getReminderDates($expirationDate, $reminderDays)
        );
    }

}
