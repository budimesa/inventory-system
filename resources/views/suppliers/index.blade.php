@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Supplier</h6>
    </div>
    <div class="card-body">        
        <div>
            <a class="modal-effect btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">Tambah Supplier</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-supplier" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Contact Person</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('suppliers.create')
@include('suppliers.edit')
@include('suppliers.delete')
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
            table = $('#table-supplier').DataTable({

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
                    "url": "{{ route('supplier.get-supplier-list') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'address',
                        name: 'address',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'contact_person',
                        name: 'contact_person',
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


        $('#table-supplier').on('click', '.btn-edit', function() {
            var editData = $(this).data('edit');
            $("input[name='idSupplierU']").val(editData.id);
            $("input[name='supplierNameU']").val(editData.name);
            $("input[name='phoneU']").val(editData.phone);
            $("input[name='contact_personU']").val(editData.contact_person);
            $("input[name='emailU']").val(editData.email);
            $("textarea[name='addressU']").val(editData.address);
        });

        function destroy(data) {
            $("input[name='supplier_id']").val(data);
        }
    </script>
@endpush
