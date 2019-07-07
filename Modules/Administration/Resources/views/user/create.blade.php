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
<form method="POST" action="{{ route('administration.users.store') }}" enctype="multipart/form-data">
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
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" id="first_name" placeholder="Enter first name.">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control" id="middle_name" placeholder="Enter middle name.">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="text-red">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" id="last_name" placeholder="Enter last name.">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender">
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                            <option value="2">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="form-control" id="address" placeholder="Enter address.">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone no.</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" id="phone" placeholder="Enter phone number.">
                    </div>

                    <div class="form-group">
                        <label for="avatar">Photo</label>
                        <p class="pr">Note: Photo file format should be jpg, jpeg and png.</p>
                        <input type="file" name="avatar" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="email" placeholder="Enter email.">
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-red">*</span></label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password.">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="text-red">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter password confirmation.">
                    </div>
                </div>
                
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <!-- /.box -->
        </div>  
    </div>
</form>

@endsection