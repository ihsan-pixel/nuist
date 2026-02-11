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

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        console.log('Form submission debug:', {
            action: form.action,
            csrfToken: csrfToken,
            formData: Object.fromEntries(formData)
        });

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
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
            console.error('Error details:', {
                message: error.message,
                stack: error.stack,
                name: error.name
            });

            let errorMessage = 'Terjadi kesalahan saat mengupload file';

            if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                errorMessage = 'Koneksi internet bermasalah. Silakan coba lagi.';
            } else if (error.message.includes('403')) {
                errorMessage = 'Akses ditolak. Token keamanan tidak valid.';
            } else if (error.message.includes('404')) {
                errorMessage = 'Halaman tidak ditemukan.';
            } else if (error.message.includes('500')) {
                errorMessage = 'Server mengalami kesalahan. Silakan coba lagi nanti.';
            }

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
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
