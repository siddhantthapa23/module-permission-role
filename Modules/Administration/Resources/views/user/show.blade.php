@extends('backend.container')

@section('foot_js')
<script>
$(document).ready(function() {
    var rTable, pTable;
    rTable = $('.roles-table').dataTable();

    $('#roleTableBody').on('click','.remove-role',function(e){
        e.preventDefault();
        $object = $(this);
        var userId = $object.attr('data-userId');
        var roleName = $object.attr('data-roleName');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            $.ajax({
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/administration/users') }}" +"/"+ userId +"/roles/"+ roleName, 
                dataType: 'json',
                success: function(response){
                    if(response.type == 'Success'){
                        var nRow = $($object).parents('tr')[0];
                        rTable.fnDeleteRow(nRow);
                        swal('Success',response.message, 'success').catch(swal.noop);
                    } else {
                        swal('Warning',response.message, 'error').catch(swal.noop);
                    }
                },
                error: function(e){
                    swal('Oops...','Something went wrong!','error').catch(swal.noop);
                }
            });
        }).catch(swal.noop);       
    });

    pTable = $('.permissions-table').dataTable();

    $('#permissionTableBody').on('click','.remove-permission',function(e){
        e.preventDefault();
        $object = $(this);
        var userId = $object.attr('data-userId');
        var permissionId = $object.attr('data-permissionId');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            $.ajax({
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/administration/users') }}" +"/"+ userId +"/permissions/"+ permissionId, 
                dataType: 'json',
                success: function(response){
                    if(response.type == 'Success'){
                        var nRow = $($object).parents('tr')[0];
                        pTable.fnDeleteRow(nRow);
                        swal('Success',response.message, 'success').catch(swal.noop);
                    } else {
                        swal('Warning',response.message, 'error').catch(swal.noop);
                    }
                },
                error: function(e){
                    swal('Oops...','Something went wrong!','error').catch(swal.noop);
                }
            });
        }).catch(swal.noop);       
    });
    
});
</script>
@endsection

@section('content')
@include('backend.breadcrumb')

@include('backend.alert')

<!-- User Details -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">User Details</h3>
                <div class="pull-right">
                    @can('edit user')
                        <a class="pull-right" href="{{ route('administration.users.edit', $user->id) }}">
                            <button class="btn btn-primary"> Edit User &nbsp;<i class="fa fa-edit"></i></button>
                        </a>
                    @endcan
                </div>
            </div>
    
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th>Avatar</th>
                            <td>
                                @if(file_exists('uploads/administration/users/'. $user->avatar) && $user->avatar != '')
                                    <img class="img-responsive" src="{{ asset('uploads/administration/users/'.$user->avatar) }}" alt="{{ $user->full_name }}" width="350" height="350">
                                @else
                                    <img class="img-responsive" src="{{ asset('uploads/default-avatar.png') }}" alt="{{ $user->full_name }}" style="height: 130px">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $user->gender }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ ($user->address) ? $user->address : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone no.</th>
                            <td>{{ ($user->phone) ? $user->phone : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $user->status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>  
</div>
<!-- User Details End -->

<!-- Roles Table -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Roles</h4>
                <div class="pull-right">
                    @role('admin')
                        <a class="pull-right" href="{{ route('administration.users.attach-role', $user->id) }}">
                            <button class="btn btn-primary"> Attach Role &nbsp;<i class="fa fa-plus"></i></button>
                        </a>
                    @endrole
                </div>
            </div>
    
            <!-- /.box-header -->
            <div class="box-body">
                <table id="roles-table" class="table table-striped table-bordered roles-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Guard Name</th>
                            @role('admin')
                                <th>Options</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody id="roleTableBody">
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                @role('admin')
                                    <td>
                                        <a href="javascript:;" title="Remove Role" class="remove-role" data-userId="{{ $user->id }}" data-roleName="{{ $role->name }}"><span class="btn btn-danger btn-round"><i class="fa fa-trash"></i></span></a>
                                    </td>
                                @endrole
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Guard Name</th>
                                @role('admin')
                                    <th>Options</th>
                                @endrole
                            </tr>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>  
</div>
<!-- Roles Table End -->

<!-- Permissions Table -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Permissions</h4>
                <div class="pull-right">
                    @role('admin')
                        <a class="pull-right" href="{{ route('administration.users.attach-permission', $user->id) }}">
                            <button class="btn btn-primary"> Attach Permission &nbsp;<i class="fa fa-plus"></i></button>
                        </a>
                    @endrole
                </div>
            </div>
    
            <!-- /.box-header -->
            <div class="box-body">
                <table id="permissions-table" class="table table-striped table-bordered permissions-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Guard Name</th>
                            @role('admin')
                                <th>Options</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody id="permissionTableBody">
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                                @role('admin')
                                    <td>
                                        <a href="javascript:;" title="Remove Permission" class="remove-permission" data-userId="{{ $user->id }}" data-permissionId="{{ $permission->id }}"><span class="btn btn-danger btn-round"><i class="fa fa-trash"></i></span></a>
                                    </td>
                                @endrole
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Guard Name</th>
                                @role('admin')
                                    <th>Options</th>
                                @endrole
                            </tr>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>  
</div>
<!-- Permissions Table End -->

@endsection