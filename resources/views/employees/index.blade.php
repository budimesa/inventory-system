@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Karyawan</h6>
    </div>
    <div class="card-body">        
        <div>
            <a class="modal-effect btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">Tambah Karyawan</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-employees" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>                       
                        <th>Division</th>  
                        <th>Phone</th>  
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('employees.create')
@include('employees.edit')
@include('employees.delete')
@endsection

@push('scripts')
<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table;
        $(document).ready(function() {
            //datatables
            table = $('#table-employees').DataTable({

                "processing": true,
                "serverSide": true,
                "info": true,
                "order": [],
                "stateSave": true,
                "lengthMenu": [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                "pageLength": 10,

                lengthChange: true,

                "ajax": {
                    "url": "{{ route('employees.get-employees-list') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name',
                    },
                    {
                        data: 'division',
                        name: 'division',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });
        });


        $('#table-employees').on('click', '.btn-edit', function() {
            var editData = $(this).data('edit');
            $("input[name='idEmployeeU']").val(editData.id);
            $("input[name='employeeNameU']").val(editData.employee_name);
            $("select[name='divisionU']").val(editData.division).trigger('change');
            $("input[name='phoneU']").val(editData.phone);
            $("input[name='emailU']").val(editData.email);
            $("input[name='nikU']").val(editData.nik);
        });

        function destroy(data) {
            $("input[name='employee_id']").val(data);
        }
    </script>
@endpush
