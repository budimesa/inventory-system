<div class="modal fade" data-backdrop="static" id="modalAdd">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Barang Masuk</h6>
                <button aria-label="Close" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="form-group">
                        <label for="incoming_code" class="form-label">Kode Barang Masuk <span class="text-danger">*</span></label>
                        <input type="text" name="incoming_code" class="form-control" id="incoming_code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="incoming_date" class="form-label">Tanggal Masuk Barang <span class="text-danger">*</span></label>                        
                        <input type="text" name="incoming_date" class="form-control" id="incoming_date" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="item_code" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="item_code" class="form-control" id="item_code" readonly placeholder="Kode Barang">
                    </div>
                    <div class="form-group">
                    <label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <select name="name" id="name" class="form-control">
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>                        
                        <select name="supplier" id="supplier" class="form-control">
                            <option value="" disabled selected>Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" min="0" class="form-control" placeholder="">
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

    var incomingCode = generateIncomingCode();

    $('#modalAdd').on('show.bs.modal', function(event) {        
        $('#incoming_code').val(incomingCode);

        $('#incoming_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        
    });
    function checkForm() {
        const incoming_date = $("input[name='incoming_date']").val();
        const item_code = $("input[name='item_code']").val();
        const supplier = $("#supplier").val();
        const quantity = $("input[name='quantity']").val();
        setLoading(true);
        resetValid();
        if (supplier == null) {
            validate('Supplier wajib di isi!', 'warning');
            $("#supplier").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        else if (incoming_date == "") {
            validate('Tanggal Masuk Barang Wajib di isi!', 'warning');
            $("input[name='incoming_date']").addClass('is-invalid');
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
        else {
            submitForm();
        }
    }

    function resetForm() {
        resetValid();
        $("select[name='name']").val('');
        $("input[name='item_code']").val('');
        $("input[name='incoming_date']").val('');
        $("select[name='supplier']").val('');
        $("input[name='quantity']").val('');
        $("input[name='notes']").val('');
        setLoading(false);
    }

    function resetValid() {
        $("select[name='name']").removeClass('is-invalid');
        $("input[name='item_code']").removeClass('is-invalid')
        $("input[name='incoming_date']").removeClass('is-invalid')
        $("select[name='supplier']").removeClass('is-invalid')
        $("input[name='quantity']").removeClass('is-invalid')
    }

    function submitForm() {
        const incoming_code = $("input[name='incoming_code']").val();
        const item_code = $("input[name='item_code']").val();
        const supplier = $("select[name='supplier']").val();
        const quantity = $("input[name='quantity']").val();
        const incoming_date = $("input[name='incoming_date']").val();
        const notes = $("input[name='notes']").val()
        $.ajax({
            type: 'POST',
            url: "{{ route('incoming_items.store') }}",
            enctype: 'multipart/form-data',
            data: {
                incoming_code: incoming_code,
                item_code: item_code,
                supplier_id: supplier,
                quantity: quantity,
                incoming_date: incoming_date,
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

    function setLoading(bool) {
        if (bool == true) {
            $('#btnLoader').removeClass('d-none');
            $('#btnSimpan').addClass('d-none');
        } else {
            $('#btnSimpan').removeClass('d-none');
            $('#btnLoader').addClass('d-none');
        }
    }

    function generateIncomingCode() {
        const randomNumber = Math.floor(10000 + Math.random() * 90000); // Generates random 5-digit number
        return 'INC' + randomNumber;
    }

    
</script>
@endsection
