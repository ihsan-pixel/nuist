@once
    @push('scripts')
        <link rel="stylesheet" href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}">
        <script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Swal === 'undefined') {
                    return;
                }

                const flashMessages = [
                    { icon: 'success', title: 'Berhasil', text: @json(session('success')) },
                    { icon: 'error', title: 'Terjadi kesalahan', text: @json(session('error')) },
                    { icon: 'warning', title: 'Perhatian', text: @json(session('warning')) },
                    { icon: 'info', title: 'Informasi', text: @json(session('info')) },
                ].filter((item) => item.text);

                const validationErrors = @json($errors->all());

                if (validationErrors.length) {
                    flashMessages.unshift({
                        icon: 'error',
                        title: 'Validasi gagal',
                        html: '<ul style="text-align:left;padding-left:18px;margin:0;">' + validationErrors.map((error) => `<li>${error}</li>`).join('') + '</ul>',
                    });
                }

                const showQueue = async () => {
                    for (const message of flashMessages) {
                        await Swal.fire({
                            confirmButtonColor: '#0e8549',
                            ...message,
                        });
                    }
                };

                showQueue();

                document.querySelectorAll('form[data-sk-swal-confirm]').forEach((form) => {
                    if (form.dataset.skSwalBound === '1') {
                        return;
                    }

                    form.dataset.skSwalBound = '1';

                    form.addEventListener('submit', function (event) {
                        event.preventDefault();

                        Swal.fire({
                            icon: form.dataset.skSwalIcon || 'warning',
                            title: form.dataset.skSwalTitle || 'Yakin melanjutkan?',
                            text: form.dataset.skSwalText || 'Tindakan ini tidak bisa dibatalkan.',
                            showCancelButton: true,
                            confirmButtonText: form.dataset.skSwalConfirmText || 'Ya, lanjutkan',
                            cancelButtonText: form.dataset.skSwalCancelText || 'Batal',
                            confirmButtonColor: '#0e8549',
                            cancelButtonColor: '#94a3b8',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endonce
