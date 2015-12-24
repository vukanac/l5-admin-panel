<?php

namespace Test;

use App\User;
use App\Role;
use App\Company;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;

use Mail;
use Mockery as m;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandSendReminderEmailCommandTest extends TestCase
{

    use DatabaseTransactions;
    
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
    public function test_send_email_have_clean_exit()
    {
        $company = factory(Company::class)->create();
        $this->mockEmail(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME'),
                $company,
                'Licence Reminder',
                'emails.company-reminder'
            );

        $this->seeInDatabase('companies', ['id' => $company->id]);

        $sendEmail = new ActionCommandSendReminderEmailCommand($company->id);
        $actualNumberOfEmailSent = $sendEmail->execute();

        $this->assertEquals(1, $actualNumberOfEmailSent);
    }
}
