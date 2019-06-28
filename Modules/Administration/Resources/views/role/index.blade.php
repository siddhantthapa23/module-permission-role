@extends('backend.container')

@section('foot_js')
<script>
    $(document).ready(function() {
        const rTable = $('.roles-table').dataTable();

        $('#roleTableBody').on('click','.remove-role',function(e){
            e.preventDefault();
            $object = $(this);
            var id = $object.attr('id');
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
                    url: "{{ url('/administration/roles') }}" + "/" + id, 
                    dataType: 'json',
                    success: function(response){
                        if(response.type == 'success'){
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
    });
</script>
@endsection

@section('content')
@include('backend.breadcrumb')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                @can('create role')
                    <h4 class="box-title">
                        <a href="{{ route('administration.roles.create') }}">
                            <button class="btn btn-primary">Create Role &nbsp;<i class="fa fa-plus"></i></button>
                        </a>
                    </h4>
                @endcan
            </div>
    
            <!-- /.box-header -->
            <div class="box-body">
                @include('backend.alert')

                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered roles-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Guard Name</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody id="roleTableBody">
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->guard_name }}</td>
                                    <td>
                                        <a href="{{ route('administration.roles.show', $role->id) }}" title="View Detail"><button class="btn btn-primary btn-round"><i class="fa fa-eye"></i></button></a>&nbsp;
    
                                        @if ($role->name != 'admin')
                                            @can('edit role')
                                                <a href="{{ route('administration.roles.edit', $role->id) }}" title="Edit role"><span class="btn btn-primary btn-round"><i class="fa fa-edit"></i></span></a>&nbsp;
                                            @endcan
                                        
                                            @can('delete role')
                                                <a href="javascript:;" title="Remove role" class="remove-role" id="{{ $role->id }}"><span class="btn btn-danger btn-round"><i class="fa fa-trash"></i></span></a>
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
                                    <th>Guard Name</th>
                                    <th>Options</th>
                                </tr>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               
            </div>
        </div>
        <!-- /.box -->
    </div>  
</div>
@endsection