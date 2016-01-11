<?php

namespace App\Http\Controllers;

use App\Company;
use App\LicenceReminderCalculator;
use App\Repositories\CompanyRepository;
use App\Repositories\ScheduleRepository;
use App\Model\ActionQueue\ActionCommandSendApprovalEmailCommand;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * The company repository instance.
     *
     * @var CompanyRepository
     */
    protected $companies;

    /**
     * Create a new controller instance.
     *
     * @param  CompanyRepository  $companies
     * @return void
     */
    public function __construct(CompanyRepository $companies)
    {
        $this->middleware('auth');
        $this->companies = $companies;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Display All Companies
         */
        $companies = $this->companies->getAllOrderedByNameAsc();
        
        return view('companies.index', [
            'companies' => $companies
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Add A New Company
         */
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        // Create The Company...
        $request->user()->companies()->create([
            'name' => $request->name,
        ]);

        return redirect('/companies');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $this->authorize('show-company', $company);
        
        return view('companies.show', [
            'company' => $company
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $this->authorize('update-company', $company);

        return view('companies.edit', [
            'company' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $this->authorize('update-company', $company);

        $this->validate($request, [
            'name' => 'required|max:255',
            'licence_expire_at' => 'date_format:Y-m-d'
        ]);

        $company->name = $request->name;

        if($request->licence_expire_at != '') {
            $company->licence_expire_at = $request->licence_expire_at;
            $company->is_suspended = false;
        }
        $company->save();

        // update schedules for SendReminderEmail(s)
        if(isset($company->licence_expire_at)) {
            $lrc = new LicenceReminderCalculator();
            $scheduleRepository = new ScheduleRepository();
            $scheduleRepository->removeAllForObject($company);
            // send reminders emails on configured days before expiration date
            $reminders = $scheduleRepository->addSendReminderEmail($company, $lrc);
            // suspend company on expiration date
            $suspensions = $scheduleRepository->addSuspendCompany($company);
            // send suspension emails on expiration date
            $suspensionEmails = $scheduleRepository->addSendSuspensionEmail($company);
            
            // send approval email now
            $approvalEmail = new ActionCommandSendApprovalEmailCommand($company->id);
            $approvalEmail->execute();
        }

        return redirect('/companies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $this->authorize('destroy-company', $company);

        $company->delete();
        
        return redirect('/companies');
    }
}
