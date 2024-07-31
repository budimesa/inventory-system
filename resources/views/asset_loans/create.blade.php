<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Transaksi</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="form-group">
                        <label for="division" class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select name="division" id="division" class="form-control select2">
                            <option value="" disabled selected>Pilih Divisi</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division->division }}">{{ $division->division }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="employee" class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                        <select name="employee" id="employee" class="form-control select2" disabled>
                            <option value="" disabled selected>Pilih Karyawan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="borrow_date" class="form-label">Tanggal Pinjam Barang <span class="text-danger">*</span></label>                        
                        <input type="text" name="borrow_date" class="form-control" id="borrow_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="planned_return_date" class="form-label">Tanggal Rencana Pengembalian <span class="text-danger">*</span></label>                        
                        <input type="text" name="planned_return_date" class="form-control" id="planned_return_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="master_item_id" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <select name="master_item_id[]" id="master_item_id" class="form-control select2" multiple="multiple">
                            {{-- <option value="" disabled selected>Pilih Nama Barang</option> --}}
                            @foreach($master_items as $master_item)
                            <option value="{{ $master_item->id }}">{{ $master_item->item_name .' - '. $master_item->item_type }}</option>
                            @endforeach
                        </select>
                    </div>               
                    <div class="form-group">
                        <label for="loan_reason" class="form-label">Alasan Pinjaman <span class="text-danger">*</span></label>
                        <input type="text" name="loan_reason" class="form-control" id="loan_reason">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled>
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button onclick="checkForm()" id="btnSimpan" class="btn btn-primary">Simpan</button>
                <button class="btn btn-light" onclick="resetForm()" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@section('addFormJS')
<script>

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%' // Mengatur lebar dropdown Select2 menjadi 100%
        });
        $('#division').change(function() {
            var division = $(this).val();
            if(division) {
                $.ajax({
                    url: '/employees-by-division/' + division,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#employee').prop('disabled', false);
                        $('#employee').empty();
                        $('#employee').append('<option value="" disabled selected>Pilih Karyawan</option>');
                        $.each(data, function(key, value) {
                            $('#employee').append('<option value="' + value.id + '">' + value.employee_name + '</option>');
                        });
                    }
                });
            } else {
                $('#employee').prop('disabled', true);
                $('#employee').empty();
                $('#employee').append('<option value="" disabled selected>Pilih Karyawan</option>');
            }
        });
    });

    $('#modalAdd').on('show.bs.modal', function(event) {        

        $('#borrow_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('#planned_return_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        
    });
    function checkForm() {
        const borrow_date = $("input[name='borrow_date']").val();
        const planned_return_date = $("input[name='planned_return_date']").val();
        const division = $("#division").val();
        const employee = $("#employee").val();
        const loan_reason = $("input[name='loan_reason']").val();
        const master_item_id = $("#master_item_id").val();
        setLoading(true);
        resetValid();
        if (division == null) {
            validate('Nama Divisi wajib di isi!', 'warning');
            $("#division").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (employee == null) {
            validate('Nama Karyawan wajib di isi!', 'warning');
            $("#employee").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (master_item_id == null) {
            validate('Nama Barang wajib di isi!', 'warning');
            $("#master_item_id").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (borrow_date == "") {
            validate('Tanggal Masuk Barang Wajib di isi!', 'warning');
            $("input[name='borrow_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (planned_return_date == "") {
            validate('Tanggal Masuk Barang Wajib di isi!', 'warning');
            $("input[name='planned_return_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (loan_reason == "") {
            validate('Alasan Pinjam wajib di isi!', 'warning');
            $("input[name='loan_reason']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else {
            submitForm();
        }
    }

    function resetForm() {
        resetValid();
        $("input[name='borrow_date']").val('');
        $("input[name='planned_return_date']").val('');
        $("select[name='division']").val(null).trigger('change');
        $("select[name='employee']").val(null).trigger('change');
        $("input[name='loan_reason']").val('');
        $('#master_item_id').val(null).trigger('change');
        setLoading(false);
    }

    function resetValid() {
        $("input[name='borrow_date']").removeClass('is-invalid')
        $("input[name='planned_return_date']").removeClass('is-invalid')
        $("select[name='division']").removeClass('is-invalid');
        $("select[name='employee']").removeClass('is-invalid')
        $("input[name='loan_reason']").removeClass('is-invalid')
        $("select[name='master_item_id']").removeClass('is-invalid')
    }

    function submitForm() {
        const borrow_date = $("input[name='borrow_date']").val();
        const planned_return_date = $("input[name='planned_return_date']").val();
        const employee = $("select[name='employee']").val();
        const loan_reason = $("input[name='loan_reason']").val();
        const master_item_id = $("#master_item_id").val();
        $.ajax({
            type: 'POST',
            url: "{{ route('asset_loans.store') }}",
            enctype: 'multipart/form-data',
            data: {
                employee: employee,
                borrow_date: borrow_date,
                planned_return_date: planned_return_date,
                loan_reason: loan_reason,
                master_item_id: master_item_id,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $('#modalAdd').modal('toggle');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil ditambah!',
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload(null, false);
                resetForm();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menambahkan item.',
                    footer: '<a href>Hubungi administrator jika masalah berlanjut.</a>'
                });
            }
        });
    }

    function setLoading(bool) {
        if (bool == true) {
            $('#btnLoader').removeClass('d-none');
            $('#btnSimpan').addClass('d-none');
        } else {
            $('#btnSimpan').removeClass('d-none');
            $('#btnLoader').addClass('d-none');
        }
    }

    
</script>
@endsection
