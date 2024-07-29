<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Barang Keluar</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="form-group">
                        <label for="outgoing_code" class="form-label">Kode Barang Keluar</label>
                        <input type="text" name="outgoing_code" class="form-control" id="outgoing_code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="outgoing_date" class="form-label">Tanggal Keluar Barang</label>                        
                        <input type="text" name="outgoing_date" class="form-control" id="outgoing_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="item_code" class="form-label">Kode Barang</label>
                        <input type="text" name="item_code" class="form-control" id="item_code" readonly placeholder="Kode Barang">
                    </div>
                    <div class="form-group">
                    <label for="name" class="form-label">Nama Barang </label>
                        <select name="name" id="name" class="form-control">
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="0" class="form-control" placeholder="">
                    </div>                    
                    <div class="form-group">
                        <label for="destination" class="form-label">Tujuan</label>
                        <input type="text" name="destination" class="form-control" id="destination">
                    </div>
                    <div class="form-group">
                        <label for="notes" class="form-label">Keterangan</label>
                        <input type="text" name="notes" class="form-control" id="notes">
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

    // Handle selection change event
    $('#name').on('change', function() {
        var selectedItemId = $(this).val();
        $.ajax({
            url: '/items/' + selectedItemId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Set the item code input value
                $('#item_code').val(data.item_code);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    var outgoingCode = generateOutgoingCode();

    $('#modalAdd').on('show.bs.modal', function(event) {
        $('#outgoing_code').val(outgoingCode);

        $('#outgoing_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        
    });
    function checkForm() {
        const outgoing_date = $("input[name='outgoing_date']").val();
        const item_code = $("input[name='item_code']").val();
        const quantity = $("input[name='quantity']").val();
        const destination = $("input[name='destination']").val();
        setLoading(true);
        resetValid();
        if (outgoing_date == "") {
            validate('Tanggal Keluar Barang Wajib di isi!', 'warning');
            $("input[name='outgoing_date']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (item_code == "") {
            validate('Kode Barang wajib di isi!', 'warning');
            $("input[name='item_code']").addClass('is-invalid');
            setLoading(false);
            return false;
        }

        else if (quantity == "") {
            validate('Quantity wajib di isi!', 'warning');
            $("input[name='quantity']").addClass('is-invalid');
            setLoading(false);
            return false;
        }

        else if (destination == "") {
            validate('Tujuan wajib di isi!', 'warning');
            $("input[name='destination']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else {
            submitForm();
        }
    }

    function resetValid() {
        $("input[name='item_code']").removeClass('is-invalid');
        $("input[name='quantity']").removeClass('is-invalid');
        $("input[name='outgoing_date']").removeClass('is-invalid');
        $("input[name='destination']").removeClass('is-invalid');            
    }

    function submitForm() {
        const outgoing_code = $("input[name='outgoing_code']").val();
        const item_code = $("input[name='item_code']").val();
        const quantity = $("input[name='quantity']").val();
        const outgoing_date = $("input[name='outgoing_date']").val();
        const destination = $("input[name='destination']").val();        
        const notes = $("input[name='notes']").val()
        $.ajax({
            type: 'POST',
            url: "{{ route('outgoing_items.store') }}",
            enctype: 'multipart/form-data',
            data: {
                outgoing_code: outgoing_code,
                item_code: item_code,                
                quantity: quantity,
                outgoing_date: outgoing_date,
                destination: destination,
                notes: notes,
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
                var errorMessage = 'Terjadi kesalahan saat menambahkan item.';
                if (xhr.status === 400) {
                    errorMessage = 'Stock Tidak Mencukupi';
                    setLoading(false);
                } else if (xhr.status === 404) {
                    errorMessage = 'Item tidak ditemukan.';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage,
                    footer: '<a href>Hubungi administrator jika masalah berlanjut.</a>'
                });
            }
        });
    }

    function resetForm() {
        resetValid();
        $("input[name='item_code']").val('');
        $("input[name='quantity']").val('');
        $("input[name='outgoing_date']").val('');
        $("input[name='destination']").val('');
        $("select[name='name']").val('');
        $("input[name='notes']").val('');
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

    function generateOutgoingCode() {
        const randomNumber = Math.floor(10000 + Math.random() * 90000); // Generates random 5-digit number
        return 'OUT' + randomNumber;
    }

    
</script>
@endsection
