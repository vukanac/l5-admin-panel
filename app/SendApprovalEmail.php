<?php

namespace App;

use App\User;
use App\Company;
use App\Role;

use Mail;

class SendApprovalEmail implements ActionInterface
{
    private $companyId;
    private $errorMessage = '';
    private $maliFromAddress = '';
    private $mailFromName = '';
    private $mailSubject = '';
    private $template = '';
    private $company;

    /**
     * Send Approval Email
     *
     * @param $company Company
     */
    public function __construct($companyId) {
        $this->companyId = $companyId;
    }

    /**
     * Get error message from run
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Prepare data used to send email message
     */
    public function prepareData()
    {
        $this->maliFromAddress = env('MAIL_FROM_ADDRESS');
        $this->mailFromName = env('MAIL_FROM_NAME');
        $this->mailSubject = 'Licence Approval';
        $this->template = 'emails.company-approval';
        $this->company = Company::findOrFail($this->companyId);
    }

    /**
     * Run worker action class to produce results.
     *
     * @return int ExitCode
     */
    public function run()
    {
        $exitCode = 0;
        try {
            $this->prepareData();
            $company = $this->company;

            Mail::send($this->template, ['company' => $company], function ($m) use ($company) {
               $m->from($this->maliFromAddress, $this->mailFromName);
               $m->to($company->email, $company->name);
               $m->subject($this->mailSubject);
            });
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $exitCode = (0 != $e->getCode()) ? $e->getCode() : 1;
        }
        return $exitCode;
    }
}
