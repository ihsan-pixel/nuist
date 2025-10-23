@section('script')
<script src="{{ asset('build/libs/leaflet/leaflet.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    let map, latitude, longitude, lokasi;

    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;

    // Inisialisasi loading status
    $('#location-info').html(`
        <div class="alert alert-info border-0 rounded-3">
            <div class="d-flex align-items-center">
                <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                <div>
                    <strong>Mendapatkan lokasi...</strong>
                    <br><small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi</small>
                </div>
            </div>
        </div>
    `);

    // Fungsi untuk membuat map
    function initMap(lat, lng) {
        if (map) {
            map.remove();
        }

        map = L.map('map', {
            center: [lat, lng],
            zoom: 17,
            zoomControl: false,
            scrollWheelZoom: false,
            attributionControl: false
        });

        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: false
        }).addTo(map);

        // Tambahkan marker lokasi
        const marker = L.circleMarker([lat, lng], {
            color: '#007bff',
            radius: 10,
            weight: 4,
            fillColor: '#007bff',
            fillOpacity: 0.3
        }).addTo(map)
          .bindPopup(`<strong>Lokasi Anda</strong><br>${lat.toFixed(6)}, ${lng.toFixed(6)}`)
          .openPopup();

        // Pastikan map tampil penuh
        setTimeout(() => {
            map.invalidateSize();
        }, 400);
    }

    // Fungsi untuk mendapatkan alamat
    function getAddress(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                lokasi = data.display_name || 'Alamat tidak ditemukan';
                $('#lokasi').val(lokasi);
            })
            .catch(() => $('#lokasi').val('Gagal mendapatkan alamat'));
    }

    // Ambil lokasi GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((pos) => {
            latitude = pos.coords.latitude;
            longitude = pos.coords.longitude;

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            getAddress(latitude, longitude);
            initMap(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success border-0 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle text-success me-3 fs-4"></i>
                        <div>
                            <strong>Lokasi berhasil didapatkan!</strong>
                            <br><small class="text-muted">Koordinat Anda terdeteksi dengan akurat</small>
                        </div>
                    </div>
                </div>
            `);
        }, (err) => {
            console.error("Gagal dapat lokasi:", err);
            $('#location-info').html(`
                <div class="alert alert-danger border-0 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-3 fs-4"></i>
                        <div>
                            <strong>Gagal mendapatkan lokasi!</strong>
                            <br><small class="text-muted">${err.message}</small>
                        </div>
                    </div>
                </div>
            `);
            initMap(-7.7956, 110.3695); // default ke Yogyakarta
        }, {
            enableHighAccuracy: true,
            timeout: 10000
        });
    }

    // ✅ Pastikan peta responsif di HP dan saat orientasi berubah
    const resizeFix = () => {
        if (map) {
            map.invalidateSize();
            setTimeout(() => map.invalidateSize(), 300);
        }
    };

    window.addEventListener('resize', resizeFix);
    window.addEventListener('orientationchange', resizeFix);

    // Jika tampilan sempat tersembunyi (misal di tab)
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) resizeFix();
    });

    // ✅ Tambahkan observer untuk memastikan map muncul sempurna saat kontainer aktif
    const observer = new ResizeObserver(resizeFix);
    observer.observe(mapContainer);
});
</script>
@endsection
