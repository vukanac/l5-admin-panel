<?php

namespace Test;

use App\User;
use App\Company;

use TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyLicenceManagementTest extends TestCase
{
    use DatabaseTransactions;

    
    public function test_set_and_get_licence_expiration_date()
    {
        $company = factory(Company::class)->create();
        $this->seeInDatabase('companies', ['id' => $company->id, 'licence_expire_at' => null]);

        $date = Carbon::now();
        $company->licence_expire_at = $date;
        $company->save();
        $this->seeInDatabase('companies', ['id' => $company->id, 'licence_expire_at' => $date->toDateString()]);
        $companyAgain = Company::find($company->id);
        $this->assertEquals($date->toDateString(), $companyAgain->licence_expire_at);
    }

}
