@extends('backend.container')

@section('content')
@include('backend.breadcrumb')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Update Role</h4>
            </div>
    
            <!-- form start -->
            <form method="POST" action="{{ route('administration.roles.update', $role->id) }}">
                {{ csrf_field() }}
                
                <div class="panel-body">
                    @include('backend.alert')
                    <div class="form-group">
                        <label for="name">Name <span class="text-red">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Enter role name." value="{{ $role->name }}">
                    </div>
                </div>

                <div class="panel-footer">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
            <!-- form end -->
        </div>
        <!-- /.box -->
    </div>  
</div>

@endsection