<?php

namespace Test;

use App\Company;
use App\Schedule;
use App\Model\ActionQueue\ActionCommandClient;
use App\Model\ActionQueue\ActionCommandFactory;
use App\Model\ActionQueue\ActionCommandInvoker;
use App\Model\ActionQueue\ActionCommandMailReceiver;
use App\Model\ActionQueue\ActionCommandSendReminderEmailCommand;

use Mail;
use Mockery as m;
use Carbon\Carbon;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandClientTest extends TestCase
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
        $schedule = factory(Schedule::class)->create([
            'run_at' => Carbon::now()->toDateString(),
            'who_object' => Company::class,
            'who_id' => $company->id,
            'action' => ActionCommandSendReminderEmailCommand::class,
            ]);

        $client = new ActionCommandClient();
        $resultsOfCommands = $client->run();

        $expected = [[
            'result' => 1,
            'command' => ActionCommandSendReminderEmailCommand::class,
            'success' => true,
            'message' => '',
            ]];

        $this->assertEquals($expected, $resultsOfCommands);
    }


    public function test_run_successfull_scheduled_action_only_once()
    {
        $company = factory(Company::class)->create();
        $date = Carbon::now();
        
        $schedule = factory(Schedule::class)->create([
            'run_at' => $date->toDateString(),
            'who_object' => Company::class,
            'who_id' => $company->id,
            'action' => ActionCommandSendReminderEmailCommand::class,
            'status' => 'new'
            ]);
        $schedule = factory(Schedule::class)->create([
            'run_at' => $date->toDateString(),
            'who_object' => Company::class,
            'who_id' => $company->id,
            'action' => ActionCommandSendReminderEmailCommand::class,
            'status' => 'done'
            ]);

        $client = new ActionCommandClient();

        $this->assertCount(2, $client->getActionsForDate($date));
        $this->assertCount(1, $client->getNewActionsForDate($date));

        $schedule = factory(Schedule::class)->create([
            'run_at' => $date->toDateString(),
            'who_object' => Company::class,
            'who_id' => $company->id,
            'action' => ActionCommandSendReminderEmailCommand::class,
            'status' => 'new'
            ]);


        $this->assertCount(3, $client->getActionsForDate($date));
        $this->assertCount(2, $client->getNewActionsForDate($date));

    }
}
