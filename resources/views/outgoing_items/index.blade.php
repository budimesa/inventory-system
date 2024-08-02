@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Transaksi Barang Keluar</h6>
    </div>
    <div class="card-body">
        <div>
            <a class="modal-effect btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">Tambah Barang Keluar</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-outgoing" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Outgoing Code</th>
                        <th>Item Code</th>
                        <th>Quantity</th>
                        <th>Outgoing Date</th>
                        <th>Destination</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('outgoing_items.create')
@include('outgoing_items.edit')
@include('outgoing_items.delete')

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
            table = $('#table-outgoing').DataTable({

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
                    "url": "{{ route('outgoing.get-outgoing-list') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'outgoing_code',
                        name: 'outgoing_code',
                    },
                    {
                        data: 'item_code',
                        name: 'item_code',
                    },
                    
                    {
                        data: 'quantity',
                        name: 'quantity',
                    },
                    {
                        data: 'outgoing_date',
                        name: 'outgoing_date',
                    },
                    {
                        data: 'destination',
                        name: 'destination',
                    },
                    {
                        data: 'notes',
                        name: 'notes',
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


        $('#table-outgoing').on('click', '.btn-edit', function() {
            var editData = $(this).data('edit');
            const outgoingDate = new Date(editData.outgoing_date);
            const formattedDate = outgoingDate.toISOString().split('T')[0];

            // Mengisi nilai ke dalam form modal edit
            $('#editItemId').val(editData.id);
            $('#editOutgoingCode').val(editData.outgoing_code);
            $('#editItemCode').val(editData.item_code);
            $('#editName').val(editData.item_code); // Sesuaikan dengan nama atribut di data edit
            $('#editSupplier').val(editData.supplier_id); // Sesuaikan dengan nama atribut di data edit
            $('#editQuantity').val(editData.quantity);
            $('#editDestination').val(editData.destination);
            $('#editOutgoingDate').val(formattedDate);
            $('#editNotes').val(editData.notes);

            // Menampilkan modal edit
            $('#modalEdit').modal('show');
        });


        
         function destroy(data) {
            $("input[name='outgoing_id']").val(data);
        }
</script>
@endpush
