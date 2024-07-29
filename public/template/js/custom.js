function validate(title, status) {
    Swal.fire({
        icon: status,
        title: title,
        showConfirmButton: false,
        timer: 1500
    });
}