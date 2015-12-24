<?php

namespace App\Model\ActionQueue;

use App\Company;
use App\Model\ActionQueue\ActionCommandInterface;

class ActionCommandSendReminderEmailCommand implements ActionCommandInterface
{
    private $receiverObj;
    private $receiverMethodParams;

    /**
     * Send Approval Email
     *
     * @param $company Company
     */
    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Prepare data used to send email message
     */
    public function prepare()
    {
        $company = Company::findOrFail($this->companyId);

        $this->receiverObj = new ActionCommandMailReceiver();
        $this->receiverMethodParams = [
            'mailSubject' => 'Licence Reminder',
            'mailFromEmail' => env('MAIL_FROM_ADDRESS'),
            'mailFromName' => env('MAIL_FROM_NAME'),
            'mailToEmail' => $company->email,
            'mailToName' => $company->name,
            'template' => 'emails.company-reminder',
            'data' => ['company' => $company],
        ];
    }

    /**
     * Invoke Receivers Method
     *
     * Prepare and invokeReceiversMethod
     *
     * - will call $receiver->methodToInvoke($params);
     * -> Mail::send($params)
     *
     * @return Number of emails sent
     */
    public function execute()
    {
        $this->prepare();
        return $this->receiverObj->methodToInvoke($this->receiverMethodParams);
    }
}
