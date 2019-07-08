@extends('backend.container')

@section('head_css')
<link href="{{ asset('backend/plugins/chosen/chosen.min.css') }}" rel="stylesheet"/>
<style>
    .chosen-container-multi .chosen-choices li.search-choice{
        margin: 8px 5px 3px 0px;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type=text], .chosen-container-active .chosen-choices{
        height: 34px;
        box-shadow: none;
    }
    .chosen-container{
        width: 100% !important;
    }
</style>
@endsection

@section('foot_js')
<script src="{{ asset('backend/plugins/chosen/chosen.jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        });
    });
</script>
@endsection

@section('content')
@include('backend.breadcrumb')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Attach Role</h4>
            </div>
    
            <!-- form start -->
            <form method="POST" action="{{ route('administration.users.attach-role.store', $userId) }}">
                {{ csrf_field() }}

                <div class="box-body">
                    @include('backend.alert')
                    <div class="form-group">
                        <label for="role">Role <span class="text-red">*</span></label>
                        <select data-placeholder="Begin typing a name to filter..." class="form-control chosen-select" multiple name="roles[]">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                
            </form>
            <!-- form end -->
        </div>
        <!-- /.box -->
    </div>  
</div>

@endsection