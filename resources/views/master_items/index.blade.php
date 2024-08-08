@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
    </div>
    <div class="card-body">        
        <div>
            <a class="modal-effect btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">Tambah Barang</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-item" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th>
                        <th>Deskripsi</th>
                        <th>Stock</th>
                        <th>Tanggal Pembelian</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('master_items.create')
@include('master_items.edit')
@include('master_items.delete')
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
            table = $('#table-item').DataTable({

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
                    "url": "{{ route('master-item.get-master-item-list') }}",
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
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'purchased_date',
                        name: 'purchased_date',
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


        $('#table-item').on('click', '.btn-edit', function() {
            var editData = $(this).data('edit');
            $("input[name='idItemU']").val(editData.id);
            $("input[name='itemNameU']").val(editData.item_name);            
            $("input[name='itemTypeU']").val(editData.item_type);
            $("input[name='stockU']").val(editData.stock);
            $("input[name='purchasedDateU']").val(editData.purchased_date);
            $("textarea[name='descriptionU']").val(editData.description);
        });

        function destroy(data) {
            $("input[name='item_id']").val(data);
        }
</script>
@if (session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Password Changed!',
            text: '{{ session('status') }}',
            showConfirmButton: true
        });
    </script>
@endif
@endpush
