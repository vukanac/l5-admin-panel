<!-- resources/views/auth/password.blade.php -->

@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Reset password
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Task Form -->
                    <form method="POST" action="/password/email" class="form-horizontal">
                        {!! csrf_field() !!}

                        <!-- E-Mail Address -->
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">E-Mail</label>

                            <div class="col-sm-6">
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-paper-plane-o"></i>Send Password Reset Link
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
                        <a href="/auth/login" class="btn btn-default">
                            <i class="fa fa-btn fa-sign-in" style="margin-right: 5px;"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
