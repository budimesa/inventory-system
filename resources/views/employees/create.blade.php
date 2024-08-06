<!-- MODAL TAMBAH -->
<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Karyawan</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Karyawan<span class="text-danger">*</span></label>
                        <input type="text" name="employee_name" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select name="division" class="form-control select2" id="division">
                            <option value="" disabled selected>Pilih Divisi</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">No HP <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div> 
                    <div class="form-group">
                        <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control" placeholder="">
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
        });
        function checkForm() {
            const employee_name = $("input[name='employee_name']").val();
            const division = $("select[name='division']").val();
            const phone = $("input[name='phone']").val();
            const email = $("input[name='email']").val();
            const nik = $("input[name='nik']").val();
            
            setLoading(true);
            resetValid();

            if (employee_name == "") {
                validate('Nama karyawan wajib di isi!', 'warning');
                $("input[name='name']").addClass('is-invalid');
                setLoading(false);
                return false;
            }
            else if (division == "") {
                validate('Nama divisi wajib di isi!', 'warning');
                $("select[name='division']").addClass('is-invalid');
                setLoading(false);
                return false;
            }

            else if (phone == "") {
                validate('No HP wajib di isi!', 'warning');
                $("input[name='phone']").addClass('is-invalid');
                setLoading(false);
                return false;
            }
           
            else {
                submitForm();
            }
        }

        function resetValid() {
            $("input[name='employee_name']").removeClass('is-invalid');
            $("select[name='division']").removeClass('is-invalid');
            $("input[name='phone']").removeClass('is-invalid');
            $("input[name='email']").removeClass('is-invalid');
            $("input[name='nik']").removeClass('is-invalid');
        }

        function resetForm() {
            resetValid();
            $("input[name='employee_name']").val('');
            $("select[name='division']").val(null).trigger('change');
            $("input[name='phone']").val('');
            $("input[name='email']").val('');
            $("input[name='nik']").val('');
            setLoading(false);
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

        function submitForm() {
            const employee_name = $("input[name='employee_name']").val();  
            const division = $("select[name='division']").val();
            const phone = $("input[name='phone']").val();
            const email = $("input[name='email']").val();
            const nik = $("input[name='nik']").val();
            
            $.ajax({
                type: 'POST',
                url: "{{ route('employees.store') }}",
                enctype: 'multipart/form-data',
                data: {
                    employee_name: employee_name,    
                    division: division,
                    phone: phone,    
                    nik: nik,
                    email: email,
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
                error: function(xhr) {
                    // Menangani kesalahan validasi dari server
                    let errors = xhr.responseJSON.errors;
                    if (errors.employee_name) {
                        validate(errors.employee_name[0], 'warning');
                        $("input[name='employee_name']").addClass('is-invalid');
                    }

                    if (errors.division) {
                        validate(errors.division[0], 'warning');
                        $("select[name='division']").addClass('is-invalid');
                    }

                    if (errors.phone) {
                        validate(errors.phone[0], 'warning');
                        $("input[name='phone']").addClass('is-invalid');
                    }

                    setLoading(false);
                }
            });
        }
    </script>
@endsection

