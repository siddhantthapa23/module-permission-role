@extends('backend.container')

@section('foot_js')
<script>
    $(document).ready(function() {
        const uTable = $('.users-table').dataTable();

        $('#userTableBody').on('click','.delete-user',function(e){
            e.preventDefault();
            $object = $(this);
            var id = $object.attr('id');
            swal({
                title: 'Are you sure?',
                text: "You want to delete user!",
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
                    url: "{{ url('/administration/users') }}" +"/"+ id,
                    dataType: 'json',
                    success: function(response){
                        if(response.type == 'Success'){
                            var nRow = $($object).parents('tr')[0];
                            uTable.fnDeleteRow(nRow);
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

        $('#example').on('change', 'select.changeStatus', function(e) {
            e.preventDefault();
            $object = $(this);
            var id = $object.attr('id');
            let status = $('#'+id).val();
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then(function () {
                $.ajax({
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/administration/users/" + id +"/change/status",
                    data:{ "id": id, 'status': status },
                    dataType: 'json',
                    success: function(response){
                        swal('Success',response.message, 'success').catch(swal.noop);
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

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a href="{{ route('administration.users.create') }}">
                        <button class="btn btn-primary">Create User &nbsp;<i class="fa fa-plus"></i></button>
                    </a>
                </h4>
            </div>
    
            <!-- /.box-header -->
            <div class="box-body">
            
                @include('backend.alert')
        
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered users-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <select id={{ $user->id }} name="status" class="form-control changeStatus">
                                            <option value="1" {{ ($user->status === 'Active') ? 'selected=selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ ($user->status === 'Inactive') ? 'selected=selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="{{ route('administration.users.show', $user->id) }}" title="View Detail"><button class="btn btn-primary btn-round"><i class="fa fa-eye"></i></button></a>&nbsp;
    
                                        @can('edit user')
                                            <a href="{{ route('administration.users.edit', $user->id) }}" title="Edit User"><span class="btn btn-primary btn-round"><i class="fa fa-edit"></i></span></a>&nbsp;
                                        @endcan
    
                                        @if ($user->id != auth()->user()->id)
                                            @can('delete user')
                                                <a href="javascript:;" title="Delete User" class="delete-user" id="{{ $user->id }}"><span class="btn btn-danger btn-round"><i class="fa fa-trash"></i></span></a>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>  
</div>

@endsection