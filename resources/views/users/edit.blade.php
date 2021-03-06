@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Edit User
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- Edit User Form -->
					<form action="/user/{{ $user->id }}" method="POST" class="form-horizontal">
						{{ csrf_field() }}
						{!! method_field('put') !!}

						<!-- Name -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Name</label>

                            <div class="col-sm-6">
                            	<input type="text" name="name" class="form-control" value="{{ $user->name }}">
                            </div>
                        </div>

                        <!-- E-Mail Address -->
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">E-Mail</label>

                            <div class="col-sm-6">
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
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
                        @can('change-user-role', $user)
                        <div class="form-group">
                            <label for="task-role" class="col-sm-3 control-label">Role</label>

                            <div class="col-sm-6">
                                <select name="role" id="user-role" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" 
                                            @if ($role == $user->role)
                                                selected
                                            @endif
                                        >{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endcan


						<!-- Add User Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-btn fa-save"></i>Save User Changes
								</button>
							</div>
						</div>
                    </form>
				</div>
			</div>
		</div>
	</div>
@endsection
