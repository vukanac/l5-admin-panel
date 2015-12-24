<?php

namespace Test;

use App\User;
use App\Role;
use App\Company;
use App\ActionCommandSendReminderEmailCommand;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandSendReminderEmailCommandTest extends TestCase
{

    use DatabaseTransactions;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_send_email_have_clean_exit()
    {
        $company = factory(Company::class)->create();

        $this->seeInDatabase('companies', ['id' => $company->id]);

        $sendEmail = new ActionCommandSendReminderEmailCommand($company->id);
        $actual = $sendEmail->execute();

        $this->assertEquals(0, $actual);
    }
}
