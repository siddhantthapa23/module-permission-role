@extends('backend.container')

@section('content')
@include('backend.breadcrumb')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Attach Permission
                </div>

                <form method="POST" action="{{ route('administration.roles.attach-permission.store', $role->id) }}">
                    {{ csrf_field() }}

                    <div class="panel-body">

                        @include('backend.alert')

                        <!-- Parent module start -->
                        @foreach ($modules as $module)
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{ strtolower(preg_replace("/[^\w]+/", "-", $module->name)) }}">
                                                {{ $module->name }}
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="{{ strtolower(preg_replace("/[^\w]+/", "-", $module->name)) }}" class="panel-collapse collapse in">
                                        <div class="panel-body">

                                            <!-- Children module start -->
                                            @foreach ($module->childrens as $children)
                                                <div class="form-group col-xs-12">
                                                    <label>{{ $children->name }}</label>
                                                    <div class="row">
                                                        @foreach ($permissions[strtolower($children->name)] as $permission)
                                                            <div class="col-sm-3">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}" {{ ($role->hasPermissionTo($permission)) ? 'checked' : '' }}> {{ $permission['name'] }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                            <!-- Children module end -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- Parent module end -->

                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection