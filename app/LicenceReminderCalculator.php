<?php

namespace App;

use Carbon\Carbon;

class LicenceReminderCalculator
{

	/**
	 * Get list of number of days before expiration day
	 * to send reminder email to company
	 *
	 * @return array
	 */
	public function getReminderDays()
	{
		$remindOnDaysStr = \Config::get('custom.remindOnDays');
        $remindOnDays = explode(',', $remindOnDaysStr);
        foreach ($remindOnDays as $key => $value) {
            $remindOnDays[$key] = intval(trim($value));
        }
        return $remindOnDays;
	}

	/**
	 * Get reminder dates
	 *
	 * based on expiration date
	 * and list of number of days before expiration day
	 *
	 * @param string $expirationDate  Expitsyion Date in Y-m-d format
	 * @param array $reminderDays  List of number of days
	 * @return array  List of dates when to remind
	 *
	 * @assert ('2016-01-30', [5, 10, 20]) == [5 =>'2016-01-25',10=>'2016-01-20',20=>'2016-01-10'].
	 */
	public function getReminderDates($expirationDate, $reminderDays)
	{
		$expireAt = Carbon::createFromFormat('Y-m-d', $expirationDate);
		$reminderDates = [];
		foreach ($reminderDays as $remindOnDay) {
			$remindOnDate = $expireAt->copy()->subDays($remindOnDay);
			$reminderDates[$remindOnDay] = $remindOnDate->toDateString();
		}
		return $reminderDates;
	}
}
