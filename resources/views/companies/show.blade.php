@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Company Details
				</div>

				<div class="panel-body">
					<div><strong>Id</strong> : {{ $company->id }}</div>
					<div>
						<strong>Name</strong> : 
						<h1>{{ $company->name }}</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
