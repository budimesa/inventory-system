<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Transaksi</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <input type="hidden" name="employeeId" id="employeeId">
                    <div class="form-group">
                        <label for="editDivision" class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select name="editDivision" id="editDivision" class="form-control select2">
                            <option value="" disabled selected>Pilih Divisi</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division->division }}">{{ $division->division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editEmployee" class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                        <select name="editEmployee" id="editEmployee" class="form-control select2">
                            <option value="" disabled selected>Pilih Karyawan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_borrow_date" class="form-label">Tanggal Pinjam Barang <span class="text-danger">*</span></label>                        
                        <input type="text" name="edit_borrow_date" class="form-control" id="edit_borrow_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="edit_planned_return_date" class="form-label">Tanggal Rencana Pengembalian <span class="text-danger">*</span></label>                        
                        <input type="text" name="edit_planned_return_date" class="form-control" id="edit_planned_return_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="edit_master_item_id" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <select name="edit_master_item_id[]" id="edit_master_item_id" class="form-control select2" multiple="multiple">
                            @foreach($master_items as $master_item)
                            <option value="{{ $master_item->id }}">{{ $master_item->item_name .' - '. $master_item->item_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_loan_reason" class="form-label">Alasan Pinjaman <span class="text-danger">*</span></label>
                        <input type="text" name="edit_loan_reason" class="form-control" id="edit_loan_reason">
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

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%' // Mengatur lebar dropdown Select2 menjadi 100%
        });

        $('#editDivision').change(function() {
            var division = $(this).val();
            if(division) {
                $.ajax({
                    url: '/employees-by-division/' + division,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editEmployee').prop('disabled', false);
                        $('#editEmployee').empty();
                        $('#editEmployee').append('<option value="" disabled selected>Pilih Karyawan</option>');
                        $.each(data, function(key, value) {
                            $('#editEmployee').append('<option value="' + value.id + '">' + value.employee_name + '</option>');
                        });
                        var employeeId = $('#employeeId').val();
                        $('#editEmployee').val(employeeId).trigger('change')
                    }
                });
            } else {
                $('#editEmployee').prop('disabled', true);
                $('#editEmployee').empty();
                $('#editEmployee').append('<option value="" disabled selected>Pilih Karyawan</option>');
            }
        });
    });

    $('#Umodaldemo8').on('show.bs.modal', function(event) {        

        $('#edit_borrow_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('#edit_planned_return_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        });

    function checkEditForm() {
        const edit_division = $("#editDivision").val();
        const edit_employee = $("#editEmployee").val();
        const edit_borrow_date = $("input[name='edit_borrow_date']").val();
        const edit_planned_return_date = $("input[name='edit_planned_return_date']").val();
        const edit_master_item_id = $("#edit_master_item_id").val();
        const edit_loan_reason = $("input[name='edit_loan_reason']").val();

        setLoadingEdit(true);
        resetValidEdit();

        if (edit_division == null) {
            validate('Nama Divisi wajib di isi!', 'warning');
            $("#editDivision").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (edit_employee == null) {
            validate('Nama Karyawan wajib di isi!', 'warning');
            $("#editEmployee").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (edit_master_item_id == null) {
            validate('Nama Barang wajib di isi!', 'warning');
            $("#edit_master_item_id").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (edit_borrow_date == "") {
            validate('Tanggal Masuk Barang Wajib di isi!', 'warning');
            $("input[name='edit_borrow_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (edit_planned_return_date == "") {
            validate('Tanggal Masuk Barang Wajib di isi!', 'warning');
            $("input[name='edit_planned_return_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (edit_loan_reason == "") {
            validate('Alasan Pinjam wajib di isi!', 'warning');
            $("input[name='edit_loan_reason']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else {
            submitEditForm();
        }
    }

    function resetValidEdit() {
        $("input[name='edit_borrow_date']").removeClass('is-invalid')
        $("input[name='edit_planned_return_date']").removeClass('is-invalid')
        $("select[name='editDivision']").removeClass('is-invalid');
        $("select[name='editEmployee']").removeClass('is-invalid')
        $("input[name='edit_loan_reason']").removeClass('is-invalid')
        $("select[name='edit_master_item_id']").removeClass('is-invalid')
    }

    
    function resetEditForm() {
        resetValidEdit();
        $("input[name='edit_borrow_date']").val('');
        $("input[name='edit_planned_return_date']").val('');
        $("select[name='editDivision']").val(null).trigger('change');
        $("select[name='editEmployee']").val(null).trigger('change');
        $("input[name='edit_loan_reason']").val('');
        $('#edit_master_item_id').val(null).trigger('change');
        setLoadingEdit(false);
    }
    
    function submitEditForm() {
        const id = $("input[name='id']").val();
        const edit_borrow_date = $("input[name='edit_borrow_date']").val();
        const edit_planned_return_date = $("input[name='edit_planned_return_date']").val();
        const edit_employee = $("select[name='editEmployee']").val();
        const edit_loan_reason = $("input[name='edit_loan_reason']").val();
        const edit_master_item_id = $("#edit_master_item_id").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-loan/${id}`,
            enctype: 'multipart/form-data',
            data: {
                employee_id: edit_employee,
                borrow_date: edit_borrow_date,
                planned_return_date: edit_planned_return_date,
                loan_reason: edit_loan_reason,
                master_item_id: edit_master_item_id,
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
