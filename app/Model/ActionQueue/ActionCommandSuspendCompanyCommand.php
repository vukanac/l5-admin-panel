<?php

namespace App\Model\ActionQueue;

use App\Company;
use App\Model\ActionQueue\ActionCommandInterface;

class ActionCommandSuspendCompanyCommand implements ActionCommandInterface
{
    private $receiverObj;
    private $receiverMethodParams;

    /**
     * Suspend Company Command
     *
     * @param $company Company
     */
    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Prepare data
     */
    public function prepare()
    {
        $this->receiverObj = new ActionCommandSuspendCompanyReceiver();
        $this->receiverMethodParams = [
            'companyId' => $this->companyId,
        ];
    }

    /**
     * Invoke Receivers Method
     *
     * Prepare and invokeReceiversMethod
     *
     * - will call $receiver->methodToInvoke($params);
     * -> company->suspend()
     *
     * @return Company instance
     */
    public function execute()
    {
        $this->prepare();
        return $this->receiverObj->methodToInvoke($this->receiverMethodParams);
    }
}
