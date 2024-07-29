
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
                        <label for="item_code" class="form-label">Kode Barang</label>
                        <input type="text" name="item_code" class="form-control" id="item_code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Item <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="text" name="price" class="form-control" placeholder="">
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
    $('#modalAdd').on('show.bs.modal', function(event) {
        var itemCode = generateItemCode();
        $('#item_code').val(itemCode);
    });
    function checkForm() {
        const name = $("input[name='name']").val();
        const price = $("input[name='price']").val();
        setLoading(true);
        resetValid();

        if (name == "") {
            validate('Nama Item wajib di isi!', 'warning');
            $("input[name='name']").addClass('is-invalid');
            setLoading(false);
            return false;
        } 
        else if (price == "") {
            validate('Harga wajib di isi!', 'warning');
            $("input[name='price']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else {
            submitForm();
        }
    }

    function resetValid() {
        $("input[name='name']").removeClass('is-invalid');
        $("input[name='price']").removeClass('is-invalid');
    }

    function submitForm() {
        const name = $("input[name='name']").val();
        const description = $("textarea[name='description']").val();
        const price = $("input[name='price']").val();

        $.ajax({
            type: 'POST',
            url: "{{ route('items.store') }}",
            enctype: 'multipart/form-data',
            data: {
                item_code: generateItemCode(),
                name: name,
                description: description,
                price: price,
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
        $("input[name='name']").val('');
        $("textarea[name='description']").val('');
        $("input[name='price']").val('');
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

    function generateItemCode() {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let randomLetters = '';
        for (let i = 0; i < 5; i++) {
            randomLetters += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        const randomNumber = Math.floor(10000 + Math.random() * 90000); // Generates random 5-digit number
        return randomLetters + randomNumber;
    }
</script>
@endsection
