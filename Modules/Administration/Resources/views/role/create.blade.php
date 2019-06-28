@extends('backend.container')

@section('head_css')
<style>
.ml {
    margin-left: 6px;
}
</style>    
@endsection

@section('content')
@include('backend.breadcrumb')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Create Role</h4>
            </div>
    
            <!-- form start -->
            <form method="POST" action="{{ route('administration.roles.store') }}">
                {{ csrf_field() }}
                
                <div class="box-body">

                    @include('backend.alert')

                    <div class="form-group">
                        <label for="name">Name <span class="text-red">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Enter role name." value="{{ old('name') }}">
                    </div>
                    
                </div>
                
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary ml">Submit</button>
                </div>

            </form>
            <!-- form end -->
        </div>
        <!-- /.box -->
    </div>  
</div>
@endsection