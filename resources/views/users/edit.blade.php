<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Ubah User</h6><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idU">
                <div class="form-group">
                    <label for="nameU" class="form-label">User <span class="text-danger">*</span></label>
                    <input type="text" name="nameU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="emailU" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="emailU" class="form-control" placeholder="">
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
        const name = $("input[name='nameU']").val();
        const email = $("input[name='emailU']").val();
        setLoadingU(true);
        resetValidU();

        if (name == "") {
            validate('Nama User wajib di isi!', 'warning');
            $("input[name='nameU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } 
        else if (email == "") {
            validate('Email wajib di isi!', 'warning');
            $("input[name='emailU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }
        else {
            submitFormU();
        }
    }

    function submitFormU() {
        const id = $("input[name='idU']").val();
        const name = $("input[name='nameU']").val();
        const email = $("input[name='emailU']").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-user/${id}`,
            enctype: 'multipart/form-data',
            data: {
                name: name,
                email: email,
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
                setLoadingU(false);
            }
        });
    }

    function resetValidU() {
        $("input[name='nameU']").removeClass('is-invalid');
        $("input[name='emailU']").removeClass('is-invalid');
    };

    function resetU() {
        resetValidU();
        $("input[name='idU']").val('');
        $("input[name='nameU']").val('');
        $("select[name='emailU']").val('');
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