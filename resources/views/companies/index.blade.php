@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

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
    <!-- Current Companies -->
    @if (count($companies) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                List of companies
            </div>

            <div class="panel-body">
                <table class="table table-striped company-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Company</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <!-- Company Name -->
                                <td class="table-text">
                                    <div>{{ $company->name }}</div>
                                </td>

                                <td>
                                @can('destroy-company', $company)
                                    <!-- Delete Button -->
                                    <form action="/company/{{ $company->id }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button id="delete-company-{{ $company->id }}">Delete Company</button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
