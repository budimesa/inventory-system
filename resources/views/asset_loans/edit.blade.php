<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Barang Masuk</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id" id="editItemId">
                    <div class="form-group">
                        <label for="editIncomingCode" class="form-label">Kode Barang Masuk</label>
                        <input type="text" name="incoming_code" class="form-control" id="editIncomingCode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editIncomingDate" class="form-label">Tanggal Masuk Barang</label>
                        <input type="text" name="incoming_date" class="form-control" id="editIncomingDate" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="editItemCode" class="form-label">Kode Barang</label>
                        <input type="text" name="item_code" class="form-control" id="editItemCode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editName" class="form-label">Nama Barang</label>
                        <select name="name" id="editName" class="form-control" disabled>
                            <option value="" disabled>Pilih Nama Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_code }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSupplier" class="form-label">Nama Supplier</label>
                        <select name="supplier" id="editSupplier" class="form-control">
                            <option value="" disabled>Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="0" id="editQuantity" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="editNotes" class="form-label">Keterangan</label>
                        <input type="text" name="editNotes" class="form-control" id="editNotes">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary d-none" id="btnLoaderEdit" type="button" disabled>
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button onclick="checkEditForm()" id="btnSimpanEdit" class="btn btn-primary">Simpan Perubahan</button>
                <button class="btn btn-light" onclick="resetEditForm()" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@section('editFormJS')
<script>
    // Handle selection change event
    $('#Umodaldemo8').on('show.bs.modal', function(event) {
        $('#editIncomingDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    function checkEditForm() {
        const itemCode = $("#editItemCode").val();
        const supplier = $("#editSupplier").val();
        const quantity = $("#editQuantity").val();
        const incomingDate = $("#editIncomingDate").val();
        const notes = $("#editNotes").val();

        setLoadingEdit(true);
        resetValidEdit();

        if (supplier == null) {
            validate('Supplier wajib di isi!', 'warning');
            $("#editSupplier").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        }
        else if (incomingDate == "") {
            validate('Tanggal Masuk Barang Wajib di isi!', 'warning');
            $("#editIncomingDate").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        }
        else if (itemCode == "") {
            validate('Kode Barang wajib di isi!', 'warning');
            $("#editItemCode").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        }

        else if (quantity == "") {
            validate('Quantity wajib di isi!', 'warning');
            $("#editQuantity").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        }
        else {
            submitEditForm();
        }
    }

    function resetValidEdit() {
        $("#editItemCode").removeClass('is-invalid');
        $("#editSupplier").removeClass('is-invalid');
        $("#editQuantity").removeClass('is-invalid');
        $("#editIncomingDate").removeClass('is-invalid');
    }

    function submitEditForm() {
        const id = $("input[name='id']").val();
        const incomingCode = $("#editIncomingCode").val();
        const itemCode = $("#editItemCode").val();
        const supplier = $("#editSupplier").val();
        const quantity = $("#editQuantity").val();
        const incomingDate = $("#editIncomingDate").val();
        const notes = $("#editNotes").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-incoming-item/${id}`,
            enctype: 'multipart/form-data',
            data: {
                item_code: itemCode,
                supplier_id: supplier,
                quantity: quantity,
                incoming_code: incomingCode,
                incoming_date: incomingDate,
                notes: notes,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $('#Umodaldemo8').modal('toggle');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil diubah!',
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload(null, false);
                resetEditForm();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengubah item.',
                    footer: '<a href>Hubungi administrator jika masalah berlanjut.</a>'
                });
            }
        });
    }

    function resetEditForm() {
        resetValidEdit();
        $('#editItemId').val('');
        $('#editIncomingCode').val('');
        $('#editItemCode').val('');
        $('#editName').val('');
        $('#editSupplier').val('');
        $('#editQuantity').val('');
        $('#editIncomingDate').val('');
        $('#editNotes').val('');
        setLoadingEdit(false);
    }

    function setLoadingEdit(bool) {
        if (bool == true) {
            $('#btnLoaderEdit').removeClass('d-none');
            $('#btnSimpanEdit').addClass('d-none');
        } else {
            $('#btnSimpanEdit').removeClass('d-none');
            $('#btnLoaderEdit').addClass('d-none');
        }
    }
</script>
@endsection
