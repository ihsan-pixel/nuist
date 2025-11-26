<!-- JAVASCRIPT -->
<script src="{{ asset('build/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('build/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('build/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ asset('build/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ asset('build/libs/node-waves/waves.min.js')}}"></script>

<script>
    // Script Change Password
    $('#change-password').on('submit', function(event) {
        event.preventDefault();
        var Id = $('#data_id').val();
        var current_password = $('#current-password').val();
        var password = $('#password').val();
        var password_confirm = $('#password-confirm').val();

        $('#current_passwordError').text('');
        $('#passwordError').text('');
        $('#password_confirmError').text('');

        $.ajax({
            url: "{{ url('update-password') }}" + "/" + Id,
            type: "POST",
            data: {
                "current_password": current_password,
                "password": password,
                "password_confirmation": password_confirm,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                $('#current_passwordError').text('');
                $('#passwordError').text('');
                $('#password_confirmError').text('');

                if (response.isSuccess == false) {
                    $('#current_passwordError').text(response.Message);
                } else if (response.isSuccess == true) {
                    setTimeout(function() {
                        window.location.href = "{{ route('root') }}";
                    }, 1000);
                }
            },
            error: function(response) {
                $('#current_passwordError').text(response.responseJSON.errors.current_password);
                $('#passwordError').text(response.responseJSON.errors.password);
                $('#password_confirmError').text(response.responseJSON.errors.password_confirmation);
            }
        });
    });

</script>

<!-- Script tambahan yang didorong oleh @push('scripts') -->
@stack('scripts')

<!-- Leaflet Core -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Draw -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<!-- Script halaman yang menggunakan @section('script') -->
@yield('script')   {{-- ← FIX WAJIB AGAR DATATABLES JALAN --}}

<!-- App JS utama -->
{{-- <script src="{{ asset('build/js/app.js')}}"></script> --}}

<!-- Script tambahan di paling bawah (jika ada) -->
@yield('script-bottom')
