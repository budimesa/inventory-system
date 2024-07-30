@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Transaksi Barang Masuk</h6>
    </div>
    <div class="card-body">
        <div>
            <a class="modal-effect btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">Tambah Barang Masuk</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="table-incoming" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Incoming Code</th>
                        <th>Item Code</th>
                        <th>Supplier Name</th>
                        <th>Quantity</th>
                        <th>Incoming Date</th>
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

@include('asset_loans.create')
@include('asset_loans.edit')
@include('asset_loans.delete')

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
            table = $('#table-incoming').DataTable({

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
                    "url": "{{ route('incoming.get-incoming-list') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'incoming_code',
                        name: 'incoming_code',
                    },
                    {
                        data: 'item_code',
                        name: 'item_code',
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name',
                    },
                    
                    {
                        data: 'quantity',
                        name: 'quantity',
                    },
                    {
                        data: 'incoming_date',
                        name: 'incoming_date',
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


        $('#table-incoming').on('click', '.btn-edit', function() {
            
            var editData = $(this).data('edit');
            const incomingDate = new Date(editData.incoming_date);
            const formattedDate = incomingDate.toISOString().split('T')[0];
            
            // Mengisi nilai ke dalam form modal edit
            $('#editItemId').val(editData.id);
            $('#editIncomingCode').val(editData.incoming_code);
            $('#editItemCode').val(editData.item_code);
            $('#editName').val(editData.item_code); // Sesuaikan dengan nama atribut di data edit
            $('#editSupplier').val(editData.supplier_id); // Sesuaikan dengan nama atribut di data edit
            $('#editQuantity').val(editData.quantity);
            $('#editIncomingDate').val(formattedDate);
            $('#editNotes').val(editData.notes);

            // Menampilkan modal edit
            $('#modalEdit').modal('show');
        });


        
         function destroy(data) {
            $("input[name='incoming_id']").val(data);
        }
</script>
@endpush
