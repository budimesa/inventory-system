@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Transaksi Barang Bermasalah</h6>
    </div>
    <div class="card-body">        
        <div class="table-responsive">
            <table class="table table-bordered" id="table-problematic-item" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Karyawan</th>
                        <th>Nama Divisi</th>
                        <th>Nama Barang</th>
                        <th>Status</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Keterangan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('problematic_items.return')

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
            table = $('#table-problematic-item').DataTable({

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
                    "url": "{{ route('problematic-item.get-problematic-item-list') }}",
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
                        data: 'item_details',
                        name: 'item_details',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'return_date',
                        name: 'return_date',
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


        $('#table-problematic-item').on('click', '.btn-return', function() {            
            var returnData = $(this).data('return');
            // // Mengisi nilai ke dalam form modal edit
            $('#returnId').val(returnData.id);
            $('#return_division').val(returnData.division);
            $('#return_employee').val(returnData.employee_name);
            $('#return_borrow_date').val(returnData.borrow_date);
            $('#return_planned_return_date').val(returnData.planned_return_date);
            $('#return_loan_reason').val(returnData.loan_reason);
            $('#item_name').val(returnData.item_name);
        });

        // function destroy(data) {
        //     $("input[name='item_id']").val(data);
        // }
</script>
@endpush
