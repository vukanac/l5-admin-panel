<?php

namespace Test;

use App\Company;
use App\Model\ActionQueue\ActionCommandMailReceiver;
use App\Model\ActionQueue\ActionCommandInvoker;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;

use Mail;
use Mockery as m;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandInvokerTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

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

    public function test_invoke_command()
    {
    	$company = factory(Company::class)->create();
        $this->mockEmail(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME'),
                $company,
                'Licence Reminder',
                'emails.company-reminder'
            );

        $commandObj = new ActionCommandSendReminderEmailCommand($company->id);
        
        $invokerObj = new ActionCommandInvoker($commandObj);
        $actualNumberOfEmailSent = $invokerObj->executeCommand(); // $commandObj->execute();

        $this->assertEquals(1, $actualNumberOfEmailSent);
    }
}
