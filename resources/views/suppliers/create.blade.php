<!-- MODAL TAMBAH -->
<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Supplier</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="supplierForm">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">No Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="4"></textarea>
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
        function checkForm() {
            const name = $("input[name='name']").val();
            const phone = $("input[name='phone']").val();
            const email = $("input[name='email']").val();
            setLoading(true);
            resetValid();

            if (name == "") {
                validate('Nama Supplier wajib di isi!', 'warning');
                $("input[name='name']").addClass('is-invalid');
                setLoading(false);
                return false;
            }
            else if (phone == "") {
                validate('No HP wajib di isi!', 'warning');
                $("input[name='phone']").addClass('is-invalid');
                setLoading(false);
                return false;
            }
            else if (email == "") {
                validate('Email wajib di isi!', 'warning');
                $("input[name='email']").addClass('is-invalid');
                setLoading(false);
                return false;
            } 
            else {
                submitForm();
            }
        }

        function resetValid() {
            $("input[name='name']").removeClass('is-invalid');
            $("input[name='phone']").removeClass('is-invalid');
            $("input[name='email']").removeClass('is-invalid');
        }

        function submitForm() {
            const name = $("input[name='name']").val();
            const phone = $("input[name='phone']").val();
            const email = $("input[name='email']").val();
            const contact_person = $("input[name='contact_person']").val();
            const address = $("textarea[name='address']").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('suppliers.store') }}",
                enctype: 'multipart/form-data',
                data: {
                    name: name,
                    phone: phone,
                    email: email,
                    contact_person: contact_person,
                    address: address,
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
                        text: 'Terjadi kesalahan saat menambahkan supplier.',
                        footer: '<a href>Hubungi administrator jika masalah berlanjut.</a>'
                    });
                }
            });
        }

        function resetForm() {
            resetValid();
            $("input[name='name']").val('');
            $("input[name='phone']").val('');
            $("input[name='email']").val('');
            $("input[name='contact_person']").val('');
            $("textarea[name='address']").val('');
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
    </script>
@endsection

