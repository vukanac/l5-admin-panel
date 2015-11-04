<!-- resources/views/companies.blade.php -->

@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
        <!-- @ include('common.errors') -->

        <!-- New Company Form -->
        <form action="/company" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <!-- Company Name -->
            <div class="form-group">
                <label for="company" class="col-sm-3 control-label">Company</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="company-name" class="form-control">
                </div>
            </div>

            <!-- Add Company Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> Add Company
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TODO: Current Companies -->
@endsection