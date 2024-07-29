<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Barang Keluar</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id" id="editItemId">
                    <div class="form-group">
                        <label for="editOutgoingCode" class="form-label">Kode Barang Keluar</label>
                        <input type="text" name="outgoing_code" class="form-control" id="editOutgoingCode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editOutgoingDate" class="form-label">Tanggal Keluar Barang</label>
                        <input type="text" name="outgoing_date" class="form-control" id="editOutgoingDate" placeholder="">
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
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="0" id="editQuantity" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="editDestination" class="form-label">Tujuan</label>
                        <input type="text" name="editDestination" class="form-control" id="editDestination">
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
    // Handle modal show event
    $('#Umodaldemo8').on('show.bs.modal', function(event) {
        $('#editOutgoingDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    function checkEditForm() {
        const outgoingDate = $("#editOutgoingDate").val();
        const itemCode = $("#editItemCode").val();        
        const quantity = $("#editQuantity").val();
        const destination = $("#editDestination").val();
        setLoadingEdit(true);
        resetValidEdit();

        if (outgoingDate == "") {
            validate('Tanggal Barang Keluar wajib di isi!', 'warning');
            $("#editOutgoingDate").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        }
        if (itemCode == "") {
            validate('Kode Barang wajib di isi!', 'warning');
            $("#editItemCode").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        } 
        if (quantity == "") {
            validate('Quantity di isi!', 'warning');
            $("#editQuantity").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        } 
        if (destination == "") {
            validate('Tujuan wajib di isi!', 'warning');
            $("#editDestination").addClass('is-invalid');
            setLoadingEdit(false);
            return false;
        } 
        else {
            submitEditForm();
        }
    }

    function resetValidEdit() {
        $("#editOutgoingDate").removeClass('is-invalid');
        $("#editItemCode").removeClass('is-invalid');        
        $("#editQuantity").removeClass('is-invalid');
        $("#editNotes").removeClass('is-invalid');
        $("#editDestination").removeClass('is-invalid');
    }

    function submitEditForm() {
        const id = $("input[name='id']").val();
        const outgoingCode = $("#editOutgoingCode").val();
        const outgoingDate = $("#editOutgoingDate").val();
        const itemCode = $("#editItemCode").val();        
        const quantity = $("#editQuantity").val();
        const notes = $("#editNotes").val();
        const destination = $("#editDestination").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-outgoing-item/${id}`,
            enctype: 'multipart/form-data',
            data: {
                outgoing_code: outgoingCode,
                item_code: itemCode,
                quantity: quantity,
                outgoing_date: outgoingDate,
                destination: destination,
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
        $('#editOutgoingCode').val('');
        $('#editItemCode').val('');
        $('#editName').val('');
        $('#editSupplier').val('');
        $('#editQuantity').val('');
        $('#editOutgoingDate').val('');
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
