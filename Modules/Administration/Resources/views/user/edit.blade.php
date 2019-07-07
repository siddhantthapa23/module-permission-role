@extends('backend.container')

@section('head_css')
<style>
    .pr {
        color: #a94442;
    }
</style>
@endsection

@section('content')
@include('backend.breadcrumb')

<!-- form start -->
<form method="POST" action="{{ route('administration.users.update', $user->id) }}" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">User Details</h4>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    @include('backend.alert')

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="text-red">*</span></label>
                                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name." value="{{ $user->first_name }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter middle name." value="{{ $user->middle_name }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="text-red">*</span></label>
                                <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name." value="{{ $user->last_name }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender">
                            <option value="0" {{ ($user->gender == 'Male') ? 'selected' : '' }}>Male</option>
                            <option value="1" {{ ($user->gender == 'Female') ? 'selected' : '' }}>Female</option>
                            <option value="2" {{ ($user->gender == 'Other') ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Enter address." value="{{ $user->address }}">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone no.</label>
                        <input type="tel" name="phone" class="form-control" placeholder="Enter phone number." value="{{ $user->phone }}">
                    </div>

                    <div class="form-group">
                        <label for="avatar">Photo</label>
                        <p class="pr">Note: Photo file format should be jpg, jpeg and png.</p>
                        @if(file_exists('uploads/administration/users/'. $user->avatar) && $user->avatar != '')
                            <img class="img-responsive" src="{{ asset('uploads/administration/users/'.$user->avatar) }}" alt="{{ $user->full_name }}">
                        @else
                            <img class="img-responsive" src="{{ asset('uploads/default-avatar.png') }}" alt="{{ $user->full_name }}" style="height: 130px">
                        @endif
                        <br>
                        <input type="file" name="avatar" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status">
                            <option value="1" {{ ($user->status == 'Active')? 'selected="selected"' : '' }}>Active</option>
                            <option value="0" {{ ($user->status == 'Inactive')? 'selected="selected"' : '' }}>Inactive</option>
                        </select>
                    </div>
                
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>  
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4  class="box-title">Login Details</h4>
                </div>
        
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <label for="email">Email <span class="text-red">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email." value="{{ $user->email }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password.">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter password confirmation.">
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <!-- /.box -->
        </div>  
    </div>
</form>

@endsection