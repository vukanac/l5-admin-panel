<?php

namespace App\Model\ActionQueue;

use Carbon\Carbon;
use App\Repositories\ScheduleRepository;
use App\ActionCommandFactory;

/**
* 
*/
class ActionCommandClient
{
    private $invoker;
    private $commands = [];
    
    function __construct()
    {
        // get list of action to be executed
        $scheduleRepository = new ScheduleRepository();
        $date = Carbon::now();
        $scheduleRows = $scheduleRepository->getActionsForDate($date);

        // look in actions list - pack all in command obj
        foreach($scheduleRows as $schedule) {
            //$actionName = ActionCommandSendApprovalEmailCommand::class;
            $actionName = $schedule->action;
            $id = $schedule->who_id;

            $commandObj = ActionCommandFactory::create($actionName, $id);
            $this->commands[] = $commandObj;
        }
        
    }

    public function run()
    {
        // Command Pattern - CLIENT - to execute command it passes the command obj to the invoker obj.
        // === ActionStrategyClient();
        // === setCommand(CommandInterface)
        // will call command->invokeReceiversMethod();

        $result = [];

        foreach ($this->commands as $commandObj) {

            $invokerObj = new ActionCommandInvoker($commandObj);
            $commandResult = $invokerObj->executeCommand();

            $result[] = ['result' => $commandResult];
        }

        return $result;
    }
}
