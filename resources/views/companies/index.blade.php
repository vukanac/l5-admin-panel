@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    New Company
                </div>
                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    @can('create-company')
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
                    @else
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>User is not authorised to Create Company.</p>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>

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
                                    <div>
                                        @can('show-company', $company)
                                        <a href="{{ url('company/'.$company->id) }}">{{ $company->name }}
                                        </a>
                                        @else
                                        <span>{{ $company->name }}</span>
                                        @endcan
                                    </div>
                                </td>
                                <td>
                                    @can('update-company', $company)
                                    <!-- Edit Button -->
                                    <a href="company/{{ $company->id }}/edit"
                                        id="edit-company-{{ $company->id }}"
                                        class="btn btn-default"
                                        role="button"
                                        >Edit</a>
                                    @else
                                        &nbsp;
                                    @endcan
                                    @can('destroy-company', $company)
                                    <!-- Delete Button -->
                                    <form action="/company/{{ $company->id }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button id="delete-company-{{ $company->id }}">Delete Company</button>
                                    </form>
                                    @else
                                        &nbsp;
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
