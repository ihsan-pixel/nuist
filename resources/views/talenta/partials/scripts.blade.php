<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function(){

window.openAreaTab = function(evt, area){

document.querySelectorAll('.area-content')
.forEach(x=>x.classList.remove('active'));

document.querySelectorAll('.tab-btn')
.forEach(x=>x.classList.remove('active'));

document.getElementById(area)?.classList.add('active');

evt.currentTarget.classList.add('active');
}


window.openSubTab = function(evt, tab){

const parent = evt.target.closest('.area-content');

parent.querySelectorAll('.sub-tab-content')
.forEach(x=>x.classList.remove('active'));

parent.querySelectorAll('.sub-tab-btn')
.forEach(x=>x.classList.remove('active'));

document.getElementById(tab)?.classList.add('active');

evt.currentTarget.classList.add('active');
}

// Handle form submissions with AJAX and SweetAlert
document.addEventListener('submit', function(e) {
    const form = e.target;

    // Check if it's one of our talenta upload forms
    if (form.id && form.id.startsWith('form-') && form.id.includes('-')) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Mengupload...';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });

                // Reset form
                form.reset();

                // Reset file input display
                const fileInput = form.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.value = '';
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengupload file'
            });
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }
});

});
</script>
