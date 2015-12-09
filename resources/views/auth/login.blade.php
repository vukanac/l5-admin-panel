<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Login
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Task Form -->
                    <form action="/auth/login" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

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
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                        </div>

                        <!-- Remember me -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <input type="checkbox" name="remember"> Remember Me
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <a href="/auth/register" class="btn btn-default">
                            <i class="fa fa-btn fa-user-plus" style="margin-right: 5px;"></i>Register
                        </a>
                        <a href="/password/email" class="btn btn-default">
                            <i class="fa fa-btn fa-heartbeat" style="margin-right: 5px;"></i>Forgot password?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
