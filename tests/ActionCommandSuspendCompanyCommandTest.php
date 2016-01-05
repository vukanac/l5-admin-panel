<?php

namespace Test;

use App\User;
use App\Role;
use App\Company;
use App\Model\ActionQueue\ActionCommandSuspendCompanyCommand;

use Mail;
use Mockery as m;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionCommandSuspendCompanyCommandTest extends TestCase
{

    use DatabaseTransactions;
    
    public function test_suspend_company_has_clean_exit()
    {
        $company = factory(Company::class)->create([
            'is_suspended' => false,
            ]);
        $this->seeInDatabase('companies', ['id' => $company->id, 'is_suspended' => false]);

        $suspendCompany = new ActionCommandSuspendCompanyCommand($company->id);
        $company = $suspendCompany->execute();

        $this->assertEquals(true, $company->is_suspended);
    }
}
