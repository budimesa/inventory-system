<!-- MODAL EDIT -->
<div class="modal fade" data-backdrop="static" id="Umodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Ubah Barang</h6><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idItemU">
                <div class="form-group">
                    <label for="itemNameU" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="itemNameU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="itemTypeU" class="form-label">Jenis Barang <span class="text-danger">*</span></label>
                    <input type="text" name="itemTypeU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="descriptionU" class="form-label">Deskripsi</label>
                    <textarea name="descriptionU" class="form-control" rows="4"></textarea>                    
                </div>                
                <div class="form-group">
                    <label for="stockU" class="form-label">Stock <span class="text-danger">*</span></label>
                    <input type="text" name="stockU" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                        <label for="purchasedDateU" class="form-label">Tanggal Pembelian Barang <span class="text-danger">*</span></label>                        
                        <input type="text" name="purchasedDateU" class="form-control datepicker" id="purchasedDateU" placeholder="">
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
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
    function checkFormU() {
        const itemName = $("input[name='itemNameU']").val();
        const itemType = $("input[name='itemTypeU']").val();
        const stock = $("input[name='stockU']").val();
        const purchased_date = $("input[name='purchasedDateU']").val();
        setLoadingU(true);
        resetValidU();

        if (itemName == "") {
            validate('Nama Item wajib di isi!', 'warning');
            $("input[name='itemNameU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        } 
        else if (itemType == "") {
            validate('Jenis Item wajib di isi!', 'warning');
            $("input[name='itemTypeU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }
        else if (stock == "") {
            validate('Stock wajib di isi!', 'warning');
            $("input[name='stockU']").addClass('is-invalid');
            setLoadingU(false);
            return false;
        }

        else if (purchased_date == "") {
            validate('Tanggal Pembelian wajib di isi!', 'warning');
            $("input[name='purchased_dateU']").addClass('is-invalid');
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
        const itemType = $("input[name='itemTypeU']").val();
        const stock = $("input[name='stockU']").val();
        const description = $("textarea[name='descriptionU']").val();
        const purchased_date = $("input[name='purchasedDateU']").val();
        
        $.ajax({
            type: 'POST',
            url: `/update-master-item/${id}`,
            enctype: 'multipart/form-data',
            data: {
                item_name: itemName,
                item_type: itemType,
                description: description,
                stock: stock,
                purchased_date: purchased_date,
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
        $("textarea[name='descriptionU']").removeClass('is-invalid');
        $("input[name='item_codeU']").removeClass('is-invalid');
        $("input[name='stockU']").removeClass('is-invalid');
        $("input[name='purchasedDateU']").removeClass('is-invalid');
    };

    function resetU() {
        resetValidU();
        $("input[name='idItemU']").val('');
        $("input[name='itemNameU']").val('');
        $("textarea[name='descriptionU']").val('');
        $("input[name='item_codeU']").val('');
        $("input[name='stockU']").val('');
        $("input[name='purchasedDateU']").removeClass('is-invalid');
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