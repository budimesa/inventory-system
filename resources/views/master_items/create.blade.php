
<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Barang</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Jenis Barang <span class="text-danger">*</span></label>
                        <input type="text" name="item_type" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="text" name="stock" class="form-control" placeholder="" id="stockInput">
                    </div>
                    <div class="form-group">
                        <label for="purchased_date" class="form-label">Tanggal Pembelian Barang <span class="text-danger">*</span></label>                        
                        <input type="text" name="purchased_date" class="form-control datepicker" id="purchased_date" placeholder="">
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
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#stockInput').on('input', function() {
            // Hanya biarkan karakter numerik dan hapus karakter yang tidak diinginkan
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });
    });

    function checkForm() {
        const item_name = $("input[name='item_name']").val();
        const item_type = $("input[name='item_type']").val();
        const stock = $("input[name='stock']").val();
        const description = $("input[name='description']").val();
        const purchased_date = $("input[name='purchased_date']").val();
        setLoading(true);
        resetValid();

        if (item_name == "") {
            validate('Nama Item wajib di isi!', 'warning');
            $("input[name='item_name']").addClass('is-invalid');
            setLoading(false);
            return false;
        } 
        if (item_type == "") {
            validate('Jenis Item wajib di isi!', 'warning');
            $("input[name='item_type']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (stock == "") {
            validate('Stock wajib di isi!', 'warning');
            $("input[name='stock']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (purchased_date == "") {
            validate('Tanggal Pembelian wajib di isi!', 'warning');
            $("input[name='purchased_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else {
            submitForm();
        }
    }

    function resetValid() {
        $("input[name='item_name']").removeClass('is-invalid');
        $("input[name='item_type']").removeClass('is-invalid');
        $("input[name='description']").removeClass('is-invalid');
        $("input[name='stock']").removeClass('is-invalid');
        $("input[name='purchased_date']").removeClass('is-invalid');
    }

    function submitForm() {
        const item_name = $("input[name='item_name']").val();
        const item_type = $("input[name='item_type']").val();
        const description = $("textarea[name='description']").val();
        const stock = $("input[name='stock']").val();
        const purchased_date = $("input[name='purchased_date']").val();
        $.ajax({
            type: 'POST',
            url: "{{ route('master-items.store') }}",
            enctype: 'multipart/form-data',
            data: {
                item_name: item_name,
                item_type: item_type,
                description: description,
                stock: stock,
                purchased_date:purchased_date,
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

    function resetForm() {
        resetValid();
        $("input[name='item_name']").val('');
        $("input[name='item_type']").val('');
        $("textarea[name='description']").val('');
        $("input[name='stock']").val('');
        $("input[name='purchased_date']").val('');
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
