<style>
    .dataTables_wrapper .dataTables_scroll .dataTables_scrollBody {
        overflow-x: auto;
    }

    .dataTables_wrapper .dataTables_scroll .dataTables_scrollBody table td:last-child,
    .dataTables_wrapper .dataTables_scroll .dataTables_scrollBody table th:last-child {
        position: sticky;
        right: 0;
        background-color: #fff;
        z-index: 1;
    }

    .dataTables_wrapper {
        overflow-x: hidden;
    }

    .table-fixed {
        table-layout: fixed;
        width: 100%;
    }
</style>
@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Transaksi Barang Bermasalah</h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="date_type">Jenis Tanggal</label>
                <select name="date_type" id="date_type" class="form-control">
                    <option value="borrow_date">Peminjaman</option>
                    <option value="return_date">Pengembalian</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="start_date">Start Date</label>
                <input type="text" id="start_date" class="form-control datepicker">
                <i class="calendar-icon"></i>
            </div>
            <div class="col-md-2">
                <label for="end_date">End Date</label>
                <input type="text" id="end_date" class="form-control datepicker">
            </div>
            <div class="col-md-3">
                <label for="filter-status">Status</label>
                <select name="filter-status" id="filter-status" class="form-control">
                    <option value="">Semua</option>
                    <option value="not_returned">Belum Dikembalikan</option>
                    <option value="returned">Telah Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button id="filter" class="btn btn-primary" title="filter submit"><i class="fas fa-search"></i></button>
                {{-- <button type="button" class="btn btn-secondary ml-1" id="reset_dates"><i class="fas fa-undo"></i></button> --}}
            </div>                
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-problematic-item" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Karyawan</th>
                        <th>Nama Divisi</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Nama Barang</th>
                        <th>Jenis Masalah</th>
                        <th>Status</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Keterangan</th>
                        <th>Diterima Oleh</th>
                        <th class="not-export-col">Actions</th>
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

        // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        
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
                    "data": function(d) {
                        d.date_type = $('#date_type').val();
                        d.start_date = $('#start_date').val(); // Ambil nilai tanggal awal
                        d.end_date = $('#end_date').val(); // Ambil nilai tanggal akhir
                        d.status = $('#filter-status').val();
                    }
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
                        data: 'borrow_date',
                        name: 'borrow_date',
                    },
                    {
                        data: 'item_details',
                        name: 'item_details',
                    },
                    {
                        data: 'asset_notes',
                        name: 'asset_notes',
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
                        data: 'received_by',
                        name: 'received_by',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    { targets: 0, width: '30px' },
                    { targets: 1, width: '150px' },
                    { targets: 2, width: '100px' },
                    { targets: 3, width: '150px' },
                    { targets: 4, width: '200px' },
                    { targets: 5, width: '200px' },
                    { targets: 6, width: '200px' },
                    { targets: 7, width: '200px' },
                    { targets: 8, width: '200px' },
                    { targets: 9, width: '200px' },
                    { targets: 10, width: '60px' },
                    
                ],
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        title: 'Laporan Transaksi Barang Bermasalah Berkala'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Laporan Transaksi Barang Bermasalah Berkala',
                        orientation: 'landscape',
                        exportOptions: {
                        columns: function (idx, data, node) {
                                // Return true for columns that should be exported
                                return $(node).hasClass('not-export-col') ? false : true;
                            }
                        },
                        customize: function (doc) {      
                            doc.content.splice(0, 1, {
                                text: [
                                    { text: 'Laporan Transaksi Barang Bermasalah Berkala\n', fontSize: 15, bold: true, alignment: 'center', margin: [0, 0, , 12] }
                                ]
                            });
                        }
                    },
                    'csv', 'print'
                ],
                autoWidth: false, // Nonaktifkan autoWidth

                // FixedColumns settings
                "scrollX": true,
                "scrollCollapse": true,
                "paging": true,
            });
            new $.fn.dataTable.FixedColumns(table, {
                leftColumns: 0, // Menentukan jumlah kolom yang akan tetap terlihat di sebelah kiri
                rightColumns: 1 // Menentukan jumlah kolom yang akan tetap terlihat di sebelah kanan
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
            $('#item_name').val(returnData.item_name + ' - ' + returnData.item_type);
        });

        // function destroy(data) {
        //     $("input[name='item_id']").val(data);
        // }

        // Filter button click event
         $('#filter').click(function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            var dateType = $('#date_type').val();

             if ((startDate && !endDate) || (!startDate && endDate)) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Please select both start and end dates.'
                });
                return;
            }

            table.draw();
        });

        $('#reset_dates').click(function() {
            $('#date_type').val('borrow_date')
            $('#start_date').val(''); // Reset nilai Start Date
            $('#end_date').val(''); // Reset nilai End Date
            $('#filter-status').val('')
            table.draw();
        });
        
</script>
@endpush
