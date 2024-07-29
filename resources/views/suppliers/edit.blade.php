<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Supplier</h6><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idSupplierU">
                <div class="form-group">
                    <label for="supplierNameU" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="supplierNameU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="phoneU" class="form-label">No Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="phoneU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="emailU" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="emailU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="contact_personU" class="form-label">Contact Person</label>
                    <input type="text" name="contact_personU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="addressU" class="form-label">Alamat</label>
                    <textarea name="addressU" class="form-control" rows="4"></textarea>
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
    function checkFormU() {
        const supplierName = $("input[name='supplierNameU']").val();
        const phone = $("input[name='phoneU']").val();
        const email = $("input[name='emailU']").val();
        setLoadingU(true);
        resetValidU();

        if (supplierName == "") {
            validate('Nama Supplier wajib di isi!', 'warning');
            $("input[name='supplierNameU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } 
        else if (phone == "") {
            validate('No HP wajib di isi!', 'warning');
            $("input[name='phoneU']").addClass('is-invalid');
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
        const id = $("input[name='idSupplierU']").val();
        const supplierName = $("input[name='supplierNameU']").val();
        const phone = $("input[name='phoneU']").val();
        const email = $("input[name='emailU']").val();
        const contact_person = $("input[name='contact_personU']").val();
        const address = $("textarea[name='addressU']").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-supplier/${id}`,
            enctype: 'multipart/form-data',
            data: {
                name: supplierName,
                phone: phone,
                address: address,
                contact_person: contact_person,
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
            }
        });
    }

    function resetValidU() {
        $("input[name='supplierNameU']").removeClass('is-invalid');
        $("input[name='phoneU']").removeClass('is-invalid');
        $("input[name='contact_personU']").removeClass('is-invalid');
        $("input[name='emailU']").removeClass('is-invalid');
        $("textarea[name='addressU']").removeClass('is-invalid');
    };

    function resetU() {
        resetValidU();
        $("input[name='idSupplierU']").val('');
        $("input[name='supplierNameU']").val('');
        $("input[name='phoneU']").val('');
        $("input[name='contact_personU']").val('');
        $("input[name='emailU']").val('');
        $("textarea[name='addressU']").val('');
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