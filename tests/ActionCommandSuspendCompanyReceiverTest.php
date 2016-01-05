<?php

namespace Test;

use App\Company;
use App\Model\ActionQueue\ActionCommandSuspendCompanyReceiver;

use Mail;
use Mockery as m;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandSuspendCompanyReceiverTest extends TestCase
{
	public function test_receiver_suspend_company()
    {
    	$company = factory(Company::class)->create(['is_suspended' => false]);
        $receiverMethodParams = [
            'companyId' => $company->id,
        ];
        
        $this->assertEquals(false, $company->is_suspended);

        $receiverObj = new ActionCommandSuspendCompanyReceiver();
        $company = $receiverObj->methodToInvoke($receiverMethodParams);

        $this->assertEquals(true, $company->is_suspended);
    }

    /**
     * expectedException InvalidArgumentException
     */
    public function test_throw_exception_when_no_company_id_has_been_sent()
    {
        $receiverMethodParams = [
            // 'companyId' => 1, // don't set required
        ];
        
        $this->setExpectedException('\Exception');
        
        $receiverObj = new ActionCommandSuspendCompanyReceiver();
        $result = $receiverObj->methodToInvoke($receiverMethodParams);
    }
}
