@section('deleteFormJS')
<script>
    function confirmDeleteItem(id) {
        Swal.fire({
            title: 'Yakin hapus data berikut?',
            text: "Data yang sudah dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Iya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitDestroyItem(id);
            }
        })
    }

    function submitDestroyItem(id) {
        Swal.fire({
            title: 'Menghapus...',
            onBeforeOpen: () => {
                Swal.showLoading()
            },
            allowOutsideClick: false
        });

        $.ajax({
            type: 'POST',
            url: `/delete-item/${id}`,
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil dihapus!',
                    text: 'Item telah dihapus.',
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload(null, false); 
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menghapus!',
                    text: xhr.responseJSON.message || 'Terjadi kesalahan saat menghapus item.',
                    showConfirmButton: true
                });
            }
        });
    }
</script>
@endsection