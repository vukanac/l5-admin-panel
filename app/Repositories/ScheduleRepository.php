<?php

namespace App\Repositories;

use App\User;
use App\Company;
use App\Schedule;

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




    public function getActionsForDate(Carbon $dateRunAt)
    {
        return Schedule::where('run_at', $dateRunAt->toDateString())
                    ->get();
    }
}