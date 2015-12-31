@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Edit Company
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- Edit Company Form -->
					<form action="/company/{{ $company->id }}" method="POST" class="form-horizontal">
						{{ csrf_field() }}
						{!! method_field('put') !!}

						<!-- Company Name -->
						<div class="form-group">
							<label for="company-name" class="col-sm-3 control-label">Company</label>

							<div class="col-sm-6">
								<input type="text" name="name" id="company-name" class="form-control" value="{{ $company->name }}">
							</div>
						</div>

						<!-- Company Licence -->
						<div class="form-group">
							<label for="company-licence-expire-at" class="col-sm-3 control-label">Licence Expiration Date</label>

							<div class="col-sm-6">
								<input type="text" name="licence_expire_at" id="company-licence-expire-at" class="form-control" value="{{ $company->licence_expire_at }}">
							</div>
						</div>

						<!-- Add Company Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-btn fa-plus"></i>Save Edit
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
