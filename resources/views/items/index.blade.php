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
                        <th>Item Code</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('items.create')
@include('items.edit')
@include('items.delete')
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
                    "url": "{{ route('item.get-item-list') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'item_code',
                        name: 'item_code',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'description',
                        name: 'description',
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
            $("input[name='itemNameU']").val(editData.name);            
            $("input[name='item_codeU']").val(editData.item_code);
            $("input[name='stockU']").val(editData.stock);
            $("input[name='priceU']").val(editData.price);
            $("textarea[name='descriptionU']").val(editData.description);
        });

        function destroy(data) {
            $("input[name='item_id']").val(data);
        }
</script>
@endpush
