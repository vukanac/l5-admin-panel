<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Company $company)
    {
        // $user = $request->user();

        // if ($user->cannot('update-company', $company)) {
        //     return 'User is not authorized to edit company';
        // }
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
    public function update(Request $request, $company)
    {
        $user = $request->user();
        if ($user->cannot('update-company', $company)) {
            return 'User is not authorized to edit company';
        }

        $company->name = $request->name;

        $company->save();

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
