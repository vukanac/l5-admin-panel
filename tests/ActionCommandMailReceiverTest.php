<?php

namespace Test;

use App\Company;
use App\Model\ActionQueue\ActionCommandMailReceiver;

use Mail;
use Mockery as m;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandMailReceiverTest extends TestCase
{
	public function mockEmail($fromEmail, $fromName, $user, $subject, $emailTemplate, $returnNumberOfEmailsSent = 1)
    {
        Mail::shouldReceive('send')->once()->with(
            $emailTemplate,
            m::on(function ($data) {
                $this->assertArrayHasKey('company', $data);
                // $this->assertContains('my variable', $data);
                return true;
            }),
            m::on(function (\Closure $closure) use ($fromEmail, $fromName, $user, $subject) {
                $message = m::mock('Illuminate\Mailer\Message');
                $message->shouldReceive('from')
                        ->once()
                        ->with($fromEmail, $fromName)
                        ->andReturn($message); // $message === m::self() // simulate the chaining
                $message->shouldReceive('to')
                        ->once()
                        ->with($user->email, $user->name)
                        ->andReturn($message);
                $message->shouldReceive('subject')
                        ->once()
                        ->with($subject);
                $closure($message);
                return true;
            })
        )->andReturn($returnNumberOfEmailsSent);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_receiver_send_email()
    {
    	$company = factory(Company::class)->create();
        $receiverMethodParams = [
            'mailSubject' => 'Licence Approval - Test Receiver',
            'mailFromEmail' => env('MAIL_FROM_ADDRESS'),
            'mailFromName' => env('MAIL_FROM_NAME'),
            'mailToEmail' => $company->email,
            'mailToName' => $company->name,
            'template' => 'emails.company-approval',
            'data' => ['company' => $company],
        ];
        $this->mockEmail(
                $receiverMethodParams['mailFromEmail'],
                $receiverMethodParams['mailFromName'],
                $company,
                $receiverMethodParams['mailSubject'],
                $receiverMethodParams['template']
            );

        $receiverObj = new ActionCommandMailReceiver();
        $actualNoEmailsSent = $receiverObj->methodToInvoke($receiverMethodParams);

        $this->assertEquals(1, $actualNoEmailsSent);
    }

    /**
     * expectedException InvalidArgumentException
     */
    public function test_throw_exception_when_no_emails_has_been_sent()
    {
        $company = factory(Company::class)->create();
        $receiverMethodParams = [
            'mailSubject' => 'Licence Approval - Test Receiver',
            'mailFromEmail' => env('MAIL_FROM_ADDRESS'),
            'mailFromName' => env('MAIL_FROM_NAME'),
            'mailToEmail' => $company->email,
            'mailToName' => $company->name,
            'template' => 'emails.company-approval',
            'data' => ['company' => $company],
        ];
        $this->mockEmail(
                $receiverMethodParams['mailFromEmail'],
                $receiverMethodParams['mailFromName'],
                $company,
                $receiverMethodParams['mailSubject'],
                $receiverMethodParams['template'],
                0
            );

        $this->setExpectedException('\Exception');
        
        $receiverObj = new ActionCommandMailReceiver();
        $result = $receiverObj->methodToInvoke($receiverMethodParams);
    }
}
