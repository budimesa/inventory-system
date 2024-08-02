<!-- MODAL TAMBAH -->
<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah User</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama User<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Konfirmasi Password<span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="">
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
            const name = $("input[name='name']").val();
            const email = $("input[name='email']").val();
            const password = $("input[name='password']").val();
            const confirm_password = $("input[name='confirm_password']").val();
            
            setLoading(true);
            resetValid();

            if (name == "") {
                validate('Nama User wajib di isi!', 'warning');
                $("input[name='name']").addClass('is-invalid');
                setLoading(false);
                return false;
            }
            else if (email == "") {
                validate('Email wajib di isi!', 'warning');
                $("select[name='email']").addClass('is-invalid');
                setLoading(false);
                return false;
            }

            else if (password == "") {
                validate('Password wajib di isi!', 'warning');
                $("input[name='password']").addClass('is-invalid');
                setLoading(false);
                return false;
            }

            else if (confirm_password == "") {
                validate('Konfirmasi password wajib di isi!', 'warning');
                $("input[name='confirm_password']").addClass('is-invalid');
                setLoading(false);
                return false;
            }

            else if (password !== confirm_password) {
                validate('Konfirmasi password tidak sesuai', 'warning');
                $("input[name='confirm_password']").addClass('is-invalid');
                setLoading(false);
                return false;
            }
           
            else {
                submitForm();
            }
        }

        function resetValid() {
            $("input[name='name']").removeClass('is-invalid');
            $("input[name='email']").removeClass('is-invalid');
            $("input[name='password']").removeClass('is-invalid');
            $("input[name='confirmation_password']").removeClass('is-invalid');
            
        }

        function resetForm() {
            resetValid();
            $("input[name='name']").val('');
            $("input[name='email']").val('');
            $("input[name='password']").val('');
            $("input[name='password']").val('');
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
            const name = $("input[name='name']").val();  
            const email = $("input[name='email']").val();
            const password = $("input[name='password']").val();
            
            $.ajax({
                type: 'POST',
                url: "{{ route('users.store') }}",
                enctype: 'multipart/form-data',
                data: {
                    name: name,    
                    email: email,
                    password: password,    
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
                    if (errors.name) {
                        validate(errors.name[0], 'warning');
                        $("input[name='name']").addClass('is-invalid');
                    }

                    if (errors.email) {
                        validate(errors.email[0], 'warning');
                        $("input[name='email']").addClass('is-invalid');
                    }

                    setLoading(false);
                }
            });
        }
    </script>
@endsection

