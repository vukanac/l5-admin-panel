<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $companies = Company::orderBy('created_at', 'asc')->get();
        //$companies = Company::orderBy('company_name', 'asc')->get();

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

        if ($validator->fails()) {
            return redirect('/companies')
                ->withInput()
                ->withErrors($validator);
        }

        // Create The Company...

        $company = new Company;
        $company->name = $request->name;
        $company->save();

        return redirect('/companies');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return 'show company: ' . $id . ' with name: ' . $company->name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        /**
         * Delete An Existing Company
         */
        Company::findOrFail($id)->delete();

        return redirect('/companies');
    }
    
}
