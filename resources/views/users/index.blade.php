@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <!-- Current Users -->
            @if (count($users) > 0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    List of users
                </div>

                <div class="panel-body">
                    <table class="table table-striped user-table">

                        <!-- Table Headings -->
                        <thead>
                            <th>User</th>
                            <th>&nbsp;</th>
                        </thead>

                        <!-- Table Body -->
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <!-- User Name -->
                                    <td class="table-text">
                                        <div>
                                            @can('show-user', $user)
                                            <a href="{{ url('user/'.$user->id) }}">{{ $user->name }}
                                            </a>
                                            @else
                                            <span>{{ $user->name }}</span>
                                            @endcan
                                        </div>
                                    </td>

                                    <td>
                                        &nbsp;
                                        
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
