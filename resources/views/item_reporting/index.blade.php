@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Laporan Barang Berkala</h6>
    </div>
    <div class="card-body">        
        {{-- <div class="row mb-3">
            <div class="col-md-3">
                <label for="start_date">Start Date</label>
                <input type="text" id="start_date" class="form-control datepicker">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date</label>
                <input type="text" id="end_date" class="form-control datepicker">
            </div>
            <div class="col-md-3 align-self-end">
                <button id="filter" class="btn btn-primary">Filter</button>
                <button type="button" class="btn btn-secondary ml-2" id="reset_dates">Reset</button>
            </div>                
        </div> --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-item-reporting" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th>                        
                        <th>Deskripsi</th>
                        <th>Tangal Pembelian</th>
                        <th>Total Stock</th>
                        <th>Stock Terkini</th>
                        <th>Total Barang Dipinjam</th>
                        <th>Total Barang Bermasalah</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

         // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        var table;
        $(document).ready(function() {
            //datatables
            table = $('#table-item-reporting').DataTable({

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
                    "url": "{{ route('reporting.get-item-report') }}",
                    "data": function(d) {
                        // d.start_date = $('#start_date').val(); // Ambil nilai tanggal awal
                        // d.end_date = $('#end_date').val(); // Ambil nilai tanggal akhir
                    }
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'item_name',
                        name: 'item_name',
                    },
                    {
                        data: 'item_type',
                        name: 'item_type',
                    },
                    {
                        data: 'description',
                        name: 'description',
                    }, 
                    {
                        data: 'purchased_date',
                        name: 'purchased_date',
                    }, 
                    {
                        data: 'total_stock',
                        name: 'total_stock',
                    },                  
                    {
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'borrowed_count',
                        name: 'borrowed_count',
                    },
                    {
                        data: 'problematic_count',
                        name: 'problematic_count',
                    },
                ],
                // Add Buttons configuration here
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        title: 'Laporan Barang Berkala'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Laporan Barang Berkala',
                        customize: function (doc) {      
                            doc.content.splice(0, 1, {
                                text: [
                                    { text: 'Laporan Barang Berkala\n', fontSize: 15, bold: true, alignment: 'center', margin: [0, 0, , 12] }
                                ]
                            });
                        }
                    },
                    'csv', 'print'
                ]
            });
        });

        // Filter button click event
        //  $('#filter').click(function() {
        //     var startDate = $('#start_date').val();
        //     var endDate = $('#end_date').val();

        //      if ((startDate && !endDate) || (!startDate && endDate)) {
        //         Swal.fire({
        //             icon: 'info',
        //             title: 'Info',
        //             text: 'Please select both start and end dates.'
        //         });
        //         return;
        //     }

        //     table.draw();
        // });

        // $('#reset_dates').click(function() {
        //     $('#start_date').val(''); // Reset nilai Start Date
        //     $('#end_date').val(''); // Reset nilai End Date
        //     table.draw();
        // });
</script>
@endpush
