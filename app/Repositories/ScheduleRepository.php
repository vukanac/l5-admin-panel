<?php

namespace App\Repositories;

use App\User;
use App\Company;
use App\Schedule;
use App\LicenceReminderCalculator;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;

use Carbon\Carbon;

class ScheduleRepository
{
    /**
     * Remove all scheduled actions for one object with id
     * 
     * eg. remove all for whoObject:App\Company with whoId: 21
     *
     * @param $whoObject
     * @param $whoId
     * @return  Deleted rows
     */
    public function removeAll($whoObject, $whoId)
    {
        return Schedule::where('who_object', $whoObject)
                    ->where('who_id', $whoId)
                    ->delete();
    }

    /**
     * Remove all scheduled actions for one object with id
     * 
     * eg. remove all for whoObject:App\Company with whoId: 21
     *
     * @param $obj  Object, eg. instantiated $company = new Company();
     * @return  Deleted rows
     */
    public function removeAllForObject($obj)
    {
        $whoObject = get_class($obj);
        $whoId = $obj->id;

        return $this->removeAll($whoObject, $whoId);
    }

    /**
     * Save one action in schedule queue
     *
     * @param $runAt  Date when to run action
     * @param $action  Name of action class
     * @param $obj  Name of object class to which action is associated
     * @param $dataArr  (optional) Array of parameters to be used with action
     * @return Schedule  An instance of saved Schedule with id
     */
    public function add($runAt, $action, $obj, $dataArr = [])
    {
        // save action in queue
        $whoObject = get_class($obj);
        $whoId = $obj->id;
        
        $schedule = new Schedule([
            'run_at' => $runAt,
            'action' => $action,
            'who_object' => $whoObject,
            'who_id' => $whoId,
            'parameters' => json_encode($dataArr),
            'status' => 'new'
            ]);
        $schedule->save();

        return $schedule;
    }

    public function addSendReminderEmail(Company $company, LicenceReminderCalculator $reminderCalculator)
    {
        if(!isset($company->licence_expire_at)) {
            throw new \Exception("Licence Expiration Date must be set first!");
        }

        // get config values in days
        $remindOnDays = $reminderCalculator->getReminderDays();
        // dates when to send reminders
        $runAts = $reminderCalculator->getReminderDates($company->licence_expire_at, $remindOnDays);

        // save reminders in queue
        $action = ActionCommandSendReminderEmailCommand::class;

        $schedules = [];
        foreach($runAts as $runAt) {
            $schedules[$runAt] = $this->add($runAt, $action, $company, []);
        }

        return $schedules;
    }

    public function getActionsForDate(Carbon $dateRunAt)
    {
        return Schedule::where('run_at', $dateRunAt->toDateString())
                    ->get();
    }

    public function getNewActionsForDate(Carbon $dateRunAt)
    {
        return Schedule::where('run_at', $dateRunAt->toDateString())
                    ->where('status', 'new')
                    ->get();
    }
}