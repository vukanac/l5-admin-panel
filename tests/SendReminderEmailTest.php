<?php

namespace Test;

use App\User;
use App\Company;
use App\Role;
use App\SendReminderEmail;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendReminderEmailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_send_email_have_clean_exit()
    {
    	$company = factory(Company::class)->create();

    	$this->seeInDatabase('companies', ['id' => $company->id]);

    	$sendEmail = new SendReminderEmail($company->id);
    	$actual = $sendEmail->run();

        $this->assertEquals(0, $actual);
    }
}
