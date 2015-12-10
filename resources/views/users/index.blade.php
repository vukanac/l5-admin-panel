@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New User
                </div>
                
                <!-- Bootstrap Boilerplate... -->
                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    @can('create-user')
                    <!-- New User Form -->
                    <form action="{{ url('user') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Name</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            </div>
                        </div>

                        <!-- E-Mail Address -->
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">E-Mail</label>

                            <div class="col-sm-6">
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="col-sm-3 control-label">Password</label>

                            <div class="col-sm-6">
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="col-sm-3 control-label">Confirm Password</label>

                            <div class="col-sm-6">
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>


                        <!-- Role -->
                        @can('change-user-role')
                        <div class="form-group">
                            <label for="task-role" class="col-sm-3 control-label">Role</label>

                            <div class="col-sm-6">
                                <select name="role" id="user-role" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endcan

                        <!-- Register Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-user-plus"></i>Add User
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <p>You are not authorised to Create User.</p>
                    @endcan
                </div>
            </div>
            <!-- Current Users -->
            @if (count($users) > 0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    List of users
                </div>

                <div class="panel-body">
                    <table class="table table-striped table-hover user-table">

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

                                    <td class="text-right">
                                        @can('update-user', $user)
                                        <!-- Edit Button -->
                                        <a href="{{ url('user/'.$user->id.'/edit') }}"
                                            id="edit-user-{{ $user->id }}"
                                            class="btn btn-default"
                                            role="button"
                                            >Edit</a>
                                        @endcan
                                        @can('destroy-user', $user)
                                        <!-- Delete Button -->
                                        <form style="display: inline;"
                                            action="{{ url('user/'.$user->id) }}"
                                            method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button id="delete-user-{{ $user->id }}"
                                                class="btn btn-default"
                                                role="button"
                                                >Delete</button>
                                        </form>
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
