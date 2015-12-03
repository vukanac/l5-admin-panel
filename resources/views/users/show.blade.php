@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					User Details
				</div>

				<div class="panel-body">
					<strong>Name</strong> : {{ $user->name }}<br>
					<strong>Email</strong> : {{ $user->email }}<br>
					<strong>Role</strong> : {{ $user->role }}<br>
				</div>
			</div>
		</div>
	</div>
@endsection
