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
        <h6 class="m-0 font-weight-bold text-primary">Transaksi Peminjaman Barang</h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="date_type">Jenis Tanggal</label>
                <select name="date_type" id="date_type" class="form-control">
                    <option value="borrow_date">Peminjaman</option>
                    <option value="planned_return_date">Rencana Pengembalian</option>
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
                <button id="filter-due-soon" class="btn btn-secondary" title="filter pengembalian dekat jatuh tempo"><i class="fas fa-hourglass-half"></i></button>
                <button id="filter-late" class="btn btn-secondary" title="filter pengembalian terlambat"><i class="fas fa-calendar-times"></i></button>
                {{-- <button type="button" class="btn btn-secondary ml-1" id="reset_dates"><i class="fas fa-undo"></i></button> --}}
            </div>                
        </div>
        <div>
            <a class="modal-effect btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">Tambah Transaksi Baru</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-fixed" id="table-loan" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Karyawan</th>
                        <th>Divisi</th>
                        <th>Telepon</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Pinjam</th>
                        <th>Rencana Tanggal Pengembalian</th>
                        <th>Alasan Pinjam</th>
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

@include('asset_loans.create')
@include('asset_loans.edit')
@include('asset_loans.return')
@include('asset_loans.delete')

@endsection

@push('scripts')
<script>
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
            table = $('#table-loan').DataTable({

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

                 ajax: {
                    url: "{{ route('loan.get-loan-list') }}",
                    data: function (d) {
                        d.due_soon = $('#filter-due-soon').hasClass('btn-filter-active') ? 1 : 0;
                        d.late = $('#filter-late').hasClass('btn-filter-active') ? 1 : 0;
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
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'item_details',
                        name: 'item_details',
                    },
                    {
                        data: 'borrow_date',
                        name: 'borrow_date',
                    },
                    {
                        data: 'planned_return_date',
                        name: 'planned_return_date',
                    },
                    {
                        data: 'loan_reason',
                        name: 'loan_reason',
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
                    { targets: 10, width: '120px' },
                    { targets: 11, width: '80px' },
                    
                ],
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        title: 'Laporan Peminjaman Barang Berkala'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Laporan Peminjaman Barang Berkala',
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
                                    { text: 'Laporan Peminjaman Barang Berkala\n', fontSize: 15, bold: true, alignment: 'center', margin: [0, 0, , 12] }
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

             // Inisialisasi FixedColumns
            new $.fn.dataTable.FixedColumns(table, {
                leftColumns: 0, // Menentukan jumlah kolom yang akan tetap terlihat di sebelah kiri
                rightColumns: 1 // Menentukan jumlah kolom yang akan tetap terlihat di sebelah kanan
            });

            $('#filter-due-soon').on('click', function() {
                $(this).toggleClass('btn-filter-active btn-filter-inactive');
                $('#filter-late').removeClass('btn-filter-active').addClass('btn-filter-inactive');
                table.ajax.reload();
            });

            $('#filter-late').on('click', function() {
                $(this).toggleClass('btn-filter-active btn-filter-inactive');
                $('#filter-due-soon').removeClass('btn-filter-active').addClass('btn-filter-inactive');
                table.ajax.reload();
            });

            // Set initial class for the buttons
            $('#filter-due-soon').addClass('btn-filter-inactive');
            $('#filter-late').addClass('btn-filter-inactive');
        });

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


        $('#table-loan').on('click', '.btn-edit', function() {
            var editData = $(this).data('edit');
            var selectedValues = editData.master_items.map(function(item) {
                return item.id;
            });
            // // Mengisi nilai ke dalam form modal edit
            $('#editId').val(editData.id);
            $('#edit_borrow_date').val(editData.borrow_date);
            $('#edit_planned_return_date').val(editData.planned_return_date);
            $('#editDivision').val(editData.division).trigger('change');
            $('#employeeId').val(editData.employee_id);
            $('#edit_loan_reason').val(editData.loan_reason);
            $('#edit_master_item_id').val(selectedValues).trigger('change');
        });

        $('#table-loan').on('click', '.btn-return', function() {
            var returnData = $(this).data('return');
            
            // Mengosongkan dropdown
            var itemSelect = $('#return_master_item_id');
            itemSelect.empty(); // Menghapus semua opsi yang ada

            // Menambahkan opsi baru ke dropdown
            returnData.master_items.forEach(function(item) {
                var newOption = new Option(item.item_name, item.id, true, true); // Membuat opsi baru
                itemSelect.append(newOption); // Menambahkan opsi ke dropdown
            });

            // Menambahkan kembali kelas dan menginisialisasi select2
            itemSelect.addClass('form-control select2').select2({
                width: '100%'
            });

            // Mengatur nilai yang sudah terpilih
            var selectedValues = returnData.master_items.map(function(item) {
                return item.id;
            });
            itemSelect.val(selectedValues).trigger('change');

            // Mengisi nilai ke dalam form modal edit
            $('#return_id').val(returnData.id);
            $('#return_division').val(returnData.division);
            $('#return_employee').val(returnData.employee_name);
            $('#return_borrow_date').val(returnData.borrow_date);
            $('#return_planned_return_date').val(returnData.planned_return_date);
            $('#return_loan_reason').val(returnData.loan_reason);
        });



         function destroy(data) {
            $("input[name='loan_id']").val(data);
        }
</script>
@endpush
