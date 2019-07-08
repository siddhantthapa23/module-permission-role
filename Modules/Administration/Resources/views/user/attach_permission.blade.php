@extends('backend.container')

@section('head_css')
<style>
.panel-heading .accordion-toggle:after {
    font-family: 'Glyphicons Halflings';  
    content: "\e114";   
    float: right;       
    color: #8aa4af;
    font-size: 14px;         
}
.panel-heading .accordion-toggle.collapsed:after {
    content: "\e080";   
}
</style>
@endsection

@section('content')
@include('backend.breadcrumb')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Attach Permission</h4>
            </div>
    
            <!-- form start -->
            <form method="POST" action="{{ route('administration.users.attach-permission.store', $user->id) }}">
                {{ csrf_field() }}

                <div class="box-body">

                    @include('backend.alert')

                    <!-- Parent module start -->
                    @foreach ($modules as $module)
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{ make_slug($module->name) }}">
                                            {{ $module->name }}
                                        </a>
                                    </h4>
                                </div>

                                <div id="{{ make_slug($module->name) }}" class="panel-collapse collapse in">
                                    <div class="box-body">

                                        <!-- Children module start -->
                                        @forelse ($module->childrens as $children)
                                            <div class="form-group col-xs-12">
                                                <label>{{ $children->name }}</label>
                                                <div class="row">
                                                    @foreach ($permissions[make_slug($children->name)] as $permission)
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}" {{ ($user->hasDirectPermission($permission)) ? 'checked' : '' }}> {{ $permission['name'] }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @empty
                                            <div class="form-group col-xs-12">
                                                <div class="row">
                                                    @foreach ($permissions[make_slug($children->name)] as $permission)
                                                        <div class="col-sm-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}" {{ ($user->hasDirectPermission($permission)) ? 'checked' : '' }}> {{ $permission['name'] }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforelse
                                        <!-- Children module end -->
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Parent module end -->

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            <!-- form end -->
        </div>
        <!-- /.box -->
    </div>  
</div>

@endsection