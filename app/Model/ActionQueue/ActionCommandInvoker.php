<?php

namespace App\Model\ActionQueue;

//use App\Model\ActionQueue\ActionCommandInterface;

/**
 * // Command Pattern - invoker
 * // --- invoker obj. knows how to execute a command (+ does bookkeeping)
 * // --- invoker obj. des not know anything about command, only command Iterface
 * $invokerObj = new Invoker();// ===  ActionStrategyClient();
 * // // want reminder email
 * // $invokerObj->setAction(new SendReminderEmail());
 * // $data = $invokerObj->execute();
 * // // want approval email
 * // $invokerObj->setAction(new SendApprovalEmail());
 * // $data = $invokerObj->execute();
 * $invokerObj->setCommand($commandObj); // === setCommand(CommandInterface)
 * $data = $invokerObj->executeCommand(); // will call command->invokeReceiversMethod();
 */
class ActionCommandInvoker
{
    
    private $command;

    /**
     * setCommand
     * @param ActionCommandIterface $command
     */
    public function __construct(ActionCommandInterface $command)
    {
        $this->command = $command;
    }

    public function executeCommand()
    {
        return $this->command->execute();
    }
}
