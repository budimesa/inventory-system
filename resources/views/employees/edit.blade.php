<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Karyawan</h6><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idEmployeeU">
                <div class="form-group">
                    <label for="employeeNameU" class="form-label">Karyawan <span class="text-danger">*</span></label>
                    <input type="text" name="employeeNameU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="name" class="form-label">Divisi <span class="text-danger">*</span></label>
                    <select name="employeeDivisionU" class="form-control select2">
                        <option value="" disabled selected>Pilih Divisi</option>
                        @foreach($divisions as $division)
                        <option value="{{ $division }}">{{ $division }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name" class="form-label">No HP <span class="text-danger">*</span></label>
                    <input type="text" name="employeePhone" class="form-control" placeholder="">
                </div> 
            </div>
            <div class="modal-footer">
                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">Simpan Perubahan <i class="fe fe-check"></i></a>
                <a href="javascript:void(0)" class="btn btn-light" onclick="resetU()" data-dismiss="modal">Batal <i class="fe fe-x"></i></a>
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
        });
    function checkFormU() {
        const employeeName = $("input[name='employeeNameU']").val();
        const phone = $("input[name='phoneU']").val();
        const division = $("input[name='divisionU']").val();
        setLoadingU(true);
        resetValidU();

        if (employeeName == "") {
            validate('Nama Karyawan wajib di isi!', 'warning');
            $("input[name='employeeNameU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } 
        else if (phone == "") {
            validate('No HP wajib di isi!', 'warning');
            $("input[name='phoneU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }
        else if (division == "") {
            validate('division wajib di isi!', 'warning');
            $("input[name='divisionU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } 
        else {
            submitFormU();
        }
    }

    function submitFormU() {
        const id = $("input[name='idEmployeeU']").val();
        const employeeName = $("input[name='employeeNameU']").val();
        const division = $("select[name='divisionU']").val();
        const phone = $("input[name='phoneU']").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-employee/${id}`,
            enctype: 'multipart/form-data',
            data: {
                name: employeeName,
                phone: phone,
                division: division,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil diubah!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#Umodaldemo8').modal('toggle');
                table.ajax.reload(null, false);
                resetU();
            }
        });
    }

    function resetValidU() {
        $("input[name='employeeNameU']").removeClass('is-invalid');
        $("input[name='divisionU']").removeClass('is-invalid');
        $("input[name='phoneU']").removeClass('is-invalid');
    };

    function resetU() {
        resetValidU();
        $("input[name='idEmployeeU']").val('');
        $("input[name='employeeNameU']").val('');
        $("select[name='divisionU']").val('');
        $("input[name='phoneU']").val('');
        setLoadingU(false);
    }

    function setLoadingU(bool) {
        if (bool == true) {
            $('#btnLoaderU').removeClass('d-none');
            $('#btnSimpanU').addClass('d-none');
        } else {
            $('#btnSimpanU').removeClass('d-none');
            $('#btnLoaderU').addClass('d-none');
        }
    }
</script>
@endsection