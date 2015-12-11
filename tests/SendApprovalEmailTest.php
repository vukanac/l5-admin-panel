<?php

namespace Test;

use App\User;
use App\Company;
use App\Role;
use App\SendApprovalEmail;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendApprovalEmailTest extends TestCase
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

    	$sae = new SendApprovalEmail($company->id);
    	$actual = $sae->run();

        $this->assertEquals(0, $actual);
    }
}
