@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('messages.welcome-to') }}
                    <br/><strong>{{ trans('messages.welcome-to-admin-panel') }}</strong>
                </div>

                <div class="panel-body">
                    {{ trans('messages.welcome-to-desc') }}
                    <br/>{{ trans('messages.welcome-to-pls-login') }}
                    <p>
                        <a href="auth/register">{{ trans('menu.register') }} </a>
                        |
                        <a href="auth/login">{{ trans('menu.login') }} </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection