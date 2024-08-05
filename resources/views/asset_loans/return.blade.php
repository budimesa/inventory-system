<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo9">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Transaksi Pengembalian</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id" id="return_id">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="return_division" class="form-label">Nama Divisi<span class="text-danger">*</span></label>
                            <input type="text" name="return_division" class="form-control" id="return_division" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="return_employee" class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                            <input type="text" name="return_employee" class="form-control" id="return_employee" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="return_borrow_date" class="form-label">Tanggal Pinjam Barang <span class="text-danger">*</span></label>                        
                            <input type="text" name="return_borrow_date" class="form-control" id="return_borrow_date" placeholder="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="return_planned_return_date" class="form-label">Tanggal Rencana Pengembalian <span class="text-danger">*</span></label>                        
                            <input type="text" name="return_planned_return_date" class="form-control" id="return_planned_return_date" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="return_master_item_id" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <select name="return_master_item_id[]" id="return_master_item_id" class="form-control select2" multiple="multiple">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="return_loan_reason" class="form-label">Alasan Pinjaman <span class="text-danger">*</span></label>
                        <input type="text" name="return_loan_reason" class="form-control" id="return_loan_reason" readonly>
                    </div>
                    <div class="form-group">
                        <label for="return_date" class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>                        
                        <input type="text" name="return_date" class="form-control" id="return_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="notes" class="form-label">Keterangan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary d-none" id="btnLoaderReturn" type="button" disabled>
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button onclick="checkReturnForm()" id="btnSimpanReturn" class="btn btn-primary">Simpan Perubahan</button>
                <button class="btn btn-light" onclick="resetReturnForm()" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@section('returnFormJS')
<script>

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%' // Mengatur lebar dropdown Select2 menjadi 100%
        });
    });

    $('#Umodaldemo9').on('show.bs.modal', function(event) {        

        $('#return_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

    });

    function checkReturnForm() {
        
        const return_master_item_id = $("#return_master_item_id").val();
        const return_date = $("input[name='return_date']").val();
        const notes = $("textarea[name='notes']").val();

        setLoadingReturn(true);
        resetValidReturn();

        if(return_master_item_id == "") {
            validate('Barang wajib di isi!', 'warning');
            $("input[name='return_master_item_id']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if(return_date == "") {
            validate('Tanggal Pengembalian wajib di isi!', 'warning');
            $("input[name='return_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (notes == "") {
            validate('Keterangan wajib di isi!', 'warning');
            $("textarea[name='notes']").addClass('is-invalid');
            setLoading(false);
            return false;
        }        
        else {
            submitReturnForm();
        }
    }

    function resetValidReturn() {
        $("input[name='return_date']").removeClass('is-invalid')
        $("textarea[name='notes']").removeClass('is-invalid');
        $("select[name='return_master_item_id']").removeClass('is-invalid')
    }

    
    function resetReturnForm() {
        resetValidReturn();
        $("input[name='return_date']").val('');
        $("textarea[name='notes']").val('');
        $('#return_master_item_id').val(null).trigger('change');
        setLoadingReturn(false);
    }
    
    function submitReturnForm() {
        const id = $("#return_id").val();
        const return_date = $("input[name='return_date']").val();
        const notes = $("textarea[name='notes']").val();
        const return_master_item_id = $("#return_master_item_id").val();

        $.ajax({
            type: 'POST',
            url: `/return-loan/${id}`,
            enctype: 'multipart/form-data',
            data: {
                return_date: return_date,
                notes: notes,
                master_item_id: return_master_item_id,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $('#Umodaldemo9').modal('toggle');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil diubah!',
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload(null, false);
                resetReturnForm();
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

    function setLoadingReturn(bool) {
        if (bool == true) {
            $('#btnLoaderReturn').removeClass('d-none');
            $('#btnSimpanReturn').addClass('d-none');
        } else {
            $('#btnSimpanReturn').removeClass('d-none');
            $('#btnLoaderReturn').addClass('d-none');
        }
    }
</script>
@endsection
