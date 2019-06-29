@extends('backend.container')

@section('foot_js')
<script>
    $(document).ready(function() {
        const pTable = $('.permissions-table').dataTable();

        $('#permissionTableBody').on('click','.remove-permission',function(e){
            e.preventDefault();
            $object = $(this);
            var roleId = $object.attr('data-roleId');
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
                    url: "{{ url('/administration/roles') }}" +"/"+ roleId +"/permissions/"+ permissionId, 
                    dataType: 'json',
                    success: function(response){
                        if(response.type == 'success'){
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

<!-- Role Details -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Role Details</h4>
            </div>
    
            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <th>Guard Name</th>
                            <td>{{ $role->guard_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box -->
    </div>  
</div>
<!-- Role Details End -->

<!-- Permissions Table -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Permissions</h4>
                @role('admin')
                    <a class="pull-right" href="{{ route('administration.roles.attach-permission', $role->id) }}">
                        <button class="btn btn-primary"> Attach Permission &nbsp;<i class="fa fa-plus"></i></button>
                    </a>
                @endrole
            </div>
    
            <div class="box-body">
                <table id="example" class="table table-striped table-bordered permissions-table" style="width:100%">
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
                                        <a href="javascript:;" title="Remove Permission" class="remove-permission" data-roleId="{{ $role->id }}" data-permissionId="{{ $permission->id }}"><span class="btn btn-danger btn-round"><i class="fa fa-trash"></i></span></a>
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
        </div>
        <!-- /.box -->
    </div>  
</div>
<!-- Permissions Table End -->

@endsection