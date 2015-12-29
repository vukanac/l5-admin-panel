<?php

namespace App\Model\ActionQueue;

use Carbon\Carbon;
use App\Repositories\ScheduleRepository;
use App\ActionCommandFactory;


class ActionCommandClient
{
    private $invoker;
    private $commands = [];

    private $scheduleRepository;
    
    function __construct(ScheduleRepository $scheduleRepository = null)
    {
        $this->initScheduleRepository($scheduleRepository);
        $this->fillCommands();
    }

    public function initScheduleRepository($scheduleRepository)
    {
        if($scheduleRepository == null) {
            $scheduleRepository = new ScheduleRepository();
        }
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getActionsForDate($date)
    {
        return $this->scheduleRepository->getActionsForDate($date);
    }

    public function getNewActionsForDate($date)
    {
        return $this->scheduleRepository->getNewActionsForDate($date);
    }

    public function fillCommands()
    {
        // get list of action to be executed
        $date = Carbon::now();
        $scheduleRows = $this->getNewActionsForDate($date);

        // look in actions list - pack all in command obj
        foreach($scheduleRows as $schedule) {
            //$actionName = ActionCommandSendApprovalEmailCommand::class;
            $actionName = $schedule->action;
            $id = $schedule->who_id;

            $commandObj = ActionCommandFactory::create($actionName, $id);
            $this->commands[] = [
                'schedule' => $schedule,
                'command' => $commandObj
                ];
        }
    }

    public function run()
    {
        // Command Pattern - CLIENT - to execute command it passes the command obj to the invoker obj.
        // === ActionStrategyClient();
        // === setCommand(CommandInterface)
        // will call command->invokeReceiversMethod();

        $result = [];

        foreach ($this->commands as $pair) {
            // TODO: How to get schedule here? in order to bookkeep.
            $commandObj = $pair['command'];

            $this->bookkeepStart($pair['schedule']);

            $success = true;
            $message = '';
            $commandResult = '';
            try {
                $invokerObj = new ActionCommandInvoker($commandObj);
                $commandResult = $invokerObj->executeCommand();
            } catch (\Exception $e) {
                $success = false;
                $message = $e->getMessage();
            }

            $result[] = [
                'command' => get_class($commandObj),
                'result' => $commandResult,
                'success' => $success,
                'message' => $message,
                ];

            $this->bookkeepFinish($success, $pair['schedule']);
        }

        return $result;
    }
    public function bookkeepStart($schedule)
    {
        $schedule->started_at = Carbon::now();
        $schedule->status = 'in_progress';
        $schedule->save();
    }
    public function bookkeepFinish($success, $schedule)
    {
        $schedule->status = $success ? 'done' : 'new';
        $schedule->finished_at = Carbon::now();
        $schedule->save();
    }
}
