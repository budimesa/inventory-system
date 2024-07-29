<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Item</h6><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="item_codeU" class="form-label">Kode Barang</label>
                    <input type="item_code" name="item_codeU" class="form-control" placeholder="">
                </div>
                <input type="hidden" name="idItemU">
                <div class="form-group">
                    <label for="itemNameU" class="form-label">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="itemNameU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="descriptionU" class="form-label">Deskripsi</label>
                    <textarea name="descriptionU" class="form-control" rows="4"></textarea>                    
                </div>                
                <div class="form-group">
                    <label for="priceU" class="form-label">Harga <span class="text-danger">*</span></label>
                    <input type="text" name="priceU" class="form-control" placeholder="">
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
        const itemName = $("input[name='itemNameU']").val();
        const price = $("input[name='priceU']").val();
        setLoadingU(true);
        resetValidU();

        if (itemName == "") {
            validate('Nama Item wajib di isi!', 'warning');
            $("input[name='itemNameU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } 
        else if (price == "") {
            validate('Harga wajib di isi!', 'warning');
            $("input[name='priceU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }
        else {
            submitFormU();
        }
    }

    function submitFormU() {
        const id = $("input[name='idItemU']").val();
        const itemName = $("input[name='itemNameU']").val();        
        const item_code = $("input[name='item_codeU']").val();
        const price = $("input[name='priceU']").val();
        const description = $("textarea[name='descriptionU']").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-item/${id}`,
            enctype: 'multipart/form-data',
            data: {
                name: itemName,
                description: description,
                price: price,
                item_code: item_code,
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
        $("input[name='itemNameU']").removeClass('is-invalid');
        $("input[name='descriptionU']").removeClass('is-invalid');
        $("input[name='item_codeU']").removeClass('is-invalid');
        $("textarea[name='priceU']").removeClass('is-invalid');
    };

    function resetU() {
        resetValidU();
        $("input[name='idItemU']").val('');
        $("input[name='itemNameU']").val('');
        $("input[name='descriptionU']").val('');
        $("input[name='item_codeU']").val('');
        $("textarea[name='priceU']").val('');
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