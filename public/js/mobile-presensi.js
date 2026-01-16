window.addEventListener('load', function() {
    let latitude, longitude, lokasi;
    let locationReadings = [];
    let readingCount = 0;
    const totalReadings = 1; // Single location reading only
    const readingInterval = 5000; // 5 seconds

    // Map variables
    let userLocationMap = null;
    let userLocationMarker = null;

    // Function to collect location readings
    function collectLocationReading(readingNumber) {
        return new Promise((resolve, reject) => {
            // Add timeout wrapper for additional safety
            const timeoutId = setTimeout(() => {
                reject(new Error(`Reading ${readingNumber} timed out`));
            }, 15000); // 15 second total timeout

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    clearTimeout(timeoutId); // Clear timeout on success

                    const reading = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        timestamp: Date.now(),
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        speed: position.coords.speed
                    };

                    // Store in sessionStorage
                    sessionStorage.setItem(`reading${readingNumber}_latitude`, reading.latitude);
                    sessionStorage.setItem(`reading${readingNumber}_longitude`, reading.longitude);
                    sessionStorage.setItem(`reading${readingNumber}_timestamp`, reading.timestamp);
                    sessionStorage.setItem(`reading${readingNumber}_accuracy`, reading.accuracy);
                    sessionStorage.setItem(`reading${readingNumber}_altitude`, reading.altitude || null);
                    sessionStorage.setItem(`reading${readingNumber}_speed`, reading.speed || null);

                    locationReadings.push(reading);
                    readingCount++;

                    // Update UI with smooth progress
                    const isComplete = readingCount >= totalReadings;
                    const progressText = isComplete ? 'Data lengkap!' : 'Mengumpulkan...';
                    const iconClass = isComplete ? 'bx bx-check-circle text-success me-2' : 'bx bx-loader-alt bx-spin me-2';
                    const infoClass = isComplete ? 'location-info success' : 'location-info info';

                    $('#location-info').html(`
                        <div class="${infoClass}">
                            <div class="d-flex align-items-center">
                                <i class="${iconClass}"></i>
                                <div>
                                    <strong class="small">Reading ${readingCount}/${totalReadings} - ${progressText}</strong>
                                    <br><small class="text-muted">Akurasi: ${Math.round(position.coords.accuracy)}m</small>
                                </div>
                            </div>
                        </div>
                    `);

                    // Update coordinates display with latest reading
                    $('#latitude').val(reading.latitude.toFixed(6));
                    $('#longitude').val(reading.longitude.toFixed(6));

                    // Update user location map
                    updateUserLocationMap(reading.latitude, reading.longitude);

                    // Get address from latest reading
                    getAddressFromCoordinates(reading.latitude, reading.longitude);

                    // Enable selfie camera after first successful location reading
                    if (readingNumber === 1 && !selfieCaptured) {
                        setTimeout(() => {
                            initializeSelfieCamera();
                        }, 1000); // Small delay to ensure UI is updated
                    }

                    resolve(reading);
                },
                function(error) {
                    clearTimeout(timeoutId); // Clear timeout on error
                    console.warn(`Reading ${readingNumber} failed:`, error);

                    // Provide user-friendly error message
                    const errorMessage = error.code === 1 ? 'Izin lokasi ditolak' :
                                       error.code === 2 ? 'Sinyal GPS lemah' :
                                       error.code === 3 ? 'Waktu habis' : 'Error tidak diketahui';

                    $('#location-info').html(`
                        <div class="location-info warning">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error-circle me-2"></i>
                                <div>
                                <strong class="small">Reading ${readingNumber} gagal</strong>
                                <br><small class="text-muted">${errorMessage} - Melanjutkan...</small>
                                </div>
                            </div>
                        </div>
                    `);

                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000, // 10 second timeout for each reading
                    maximumAge: 30000
                }
            );
        });
    }

    // Start collecting multiple readings - enhanced for reliability with GPS fallback
    async function startLocationCollection() {
        let successfulReadings = 0;
        let lastSuccessfulReading = null;
        let consecutiveFailures = 0;
        const maxConsecutiveFailures = 3;

        try {
            for (let i = 1; i <= totalReadings; i++) {
                try {
                    const reading = await collectLocationReading(i);
                    successfulReadings++;
                    lastSuccessfulReading = reading;
                    consecutiveFailures = 0; // Reset failure counter

                    // Enable presensi button after first successful reading
                    if (successfulReadings === 1) {
                        latitude = reading.latitude;
                        longitude = reading.longitude;

                        // Enable presensi button early
                        var hasPresensi = false; // Will be set by server-side logic
                        var allPresensiComplete = false; // Will be set by server-side logic
                        var buttonText = "Ambil Selfie";
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-camera me-1"></i>' + buttonText);
                    }

                    // Wait between readings (except for the last one)
                    if (i < totalReadings) {
                        await new Promise(resolve => setTimeout(resolve, readingInterval));
                    }
                } catch (readingError) {
                    console.warn(`Reading ${i} failed:`, readingError);
                    consecutiveFailures++;

                    // If too many consecutive failures, try alternative approach
                    if (consecutiveFailures >= maxConsecutiveFailures) {
                        console.log('Too many consecutive failures, trying alternative GPS settings...');
                        await tryAlternativeGPSApproach(i);
                        consecutiveFailures = 0; // Reset after alternative attempt
                        i--; // Retry the same reading number
                        continue;
                    }

                    // Continue to next reading instead of failing completely
                    // Add a small delay before next attempt
                    if (i < totalReadings) {
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                    continue;
                }
            }

            // Final status update based on successful readings
            if (successfulReadings > 0) {
                latitude = lastSuccessfulReading.latitude;
                longitude = lastSuccessfulReading.longitude;

                const successMessage = successfulReadings === totalReadings ?
                    'Semua reading berhasil!' : `${successfulReadings}/${totalReadings} reading berhasil`;

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle text-success me-2"></i>
                            <div>
                                <strong class="small">Data lokasi lengkap!</strong>
                                <br><small class="text-muted">${successMessage}</small>
                            </div>
                        </div>
                    </div>
                `);
            } else {
                // No successful readings at all - provide detailed troubleshooting
                await showGPSTroubleshootingGuide();
                return;
            }

        } catch (error) {
            console.error('Critical error in location collection:', error);
            await showGPSTroubleshootingGuide();
        }
    }

    // Alternative GPS approach for when standard geolocation fails
    async function tryAlternativeGPSApproach(readingNumber) {
        return new Promise((resolve, reject) => {
            // Try with different settings
            const alternativeTimeout = setTimeout(() => {
                reject(new Error('Alternative GPS approach timed out'));
            }, 20000);

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    clearTimeout(alternativeTimeout);
                    console.log('Alternative GPS approach succeeded');

                    const reading = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        timestamp: Date.now(),
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        speed: position.coords.speed
                    };

                    // Update UI with success message
                    $('#location-info').html(`
                        <div class="location-info success">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                <div>
                                <strong class="small">Reading ${readingNumber} berhasil (alt)</strong>
                                <br><small class="text-muted">Akurasi: ${Math.round(position.coords.accuracy)}m</small>
                                </div>
                            </div>
                        </div>
                    `);

                    resolve(reading);
                },
                function(error) {
                    clearTimeout(alternativeTimeout);
                    reject(error);
                },
                {
                    enableHighAccuracy: false, // Try without high accuracy first
                    timeout: 15000,
                    maximumAge: 60000 // Allow older cached positions
                }
            );
        });
    }

    // Comprehensive GPS troubleshooting guide
    async function showGPSTroubleshootingGuide() {
        $('#location-info').html(`
            <div class="location-info error">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-2"></i>
                    <div>
                        <strong class="small">GPS Tidak Tersedia</strong>
                        <br><small class="text-muted">Coba langkah berikut:</small>
                    </div>
                </div>
                <div style="margin-top: 8px; font-size: 11px;">
                    <div style="margin-bottom: 4px;"><i class="bx bx-check-circle text-success me-1"></i> Pastikan GPS aktif</div>
                    <div style="margin-bottom: 4px;"><i class="bx bx-check-circle text-success me-1"></i> Berikan izin lokasi ke browser</div>
                    <div style="margin-bottom: 4px;"><i class="bx bx-check-circle text-success me-1"></i> Coba di luar ruangan</div>
                    <div style="margin-bottom: 4px;"><i class="bx bx-refresh text-primary me-1"></i> Refresh halaman</div>
                </div>
            </div>
        `);

        $('#btn-presensi').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Error');

        // Auto-retry after 10 seconds
        setTimeout(() => {
            if (locationReadings.length === 0) {
                console.log('Auto-retrying GPS collection...');
                $('#location-info').html(`
                    <div class="location-info info">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-loader-alt bx-spin me-2"></i>
                            <div>
                                <strong class="small">Mencoba lagi...</strong>
                                <br><small class="text-muted">Reading 1/1 - Auto retry</small>
                            </div>
                        </div>
                    </div>
                `);
                startLocationCollection();
            }
        }, 10000);
    }

    // Initialize user location map
    function initializeUserLocationMap() {
        // Defensive: avoid initializing the same Leaflet container more than once.
        const container = document.getElementById('user-location-map');
        if (!container) return;
        // If Leaflet already attached an id to the element, skip initialization
        if (container._leaflet_id) {
            // Remove existing map instance if it exists
            if (userLocationMap) {
                userLocationMap.remove();
                userLocationMap = null;
            }
        }
        if (userLocationMap) return; // Already initialized

        userLocationMap = L.map('user-location-map').setView([-6.2, 106.816666], 13); // Default to Jakarta

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(userLocationMap);

        // Hide placeholder
        $('#map-placeholder').hide();
    }

    // Update user location map
    function updateUserLocationMap(lat, lng) {
        if (!userLocationMap) {
            initializeUserLocationMap();
        }

        if (!userLocationMap) return; // Still not initialized

        // Remove existing marker
        if (userLocationMarker) {
            userLocationMap.removeLayer(userLocationMarker);
        }

        // Add new marker
        userLocationMarker = L.marker([lat, lng]).addTo(userLocationMap)
            .bindPopup('Lokasi Anda saat ini')
            .openPopup();

        // Center map on location
        userLocationMap.setView([lat, lng], 16);
    }

        // Check if geolocation is supported
        if (navigator.geolocation) {
            startLocationCollection();
        } else {
            $('#location-info').html(`
                <div class="location-info error">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-2"></i>
                        <div>
                            <strong class="small">Browser tidak mendukung GPS</strong>
                            <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                        </div>
                    </div>
                </div>
            `);
            $('#btn-presensi').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Tidak Didukung');
        }

    // Get address from coordinates
    function getAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    lokasi = data.display_name;
                    $('#lokasi').val(lokasi);
                }
            })
            .catch(error => {
                console.error('Error getting address:', error);
                $('#lokasi').val('Tidak dapat mendapatkan alamat');
            });
    }

    // Selfie variables
    let selfieStream = null;
    let selfieCaptured = false;

    // Initialize selfie camera
    async function initializeSelfieCamera() {
        try {
            // Check if required DOM elements exist
            const video = document.getElementById('selfie-video');
            const container = document.getElementById('selfie-container');
            const captureBtn = document.getElementById('btn-capture-selfie');
            const statusElement = document.getElementById('selfie-status');

            if (!video || !container || !captureBtn || !statusElement) {
                console.error('Required DOM elements for selfie camera not found');
                throw new Error('DOM elements not ready');
            }

            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 480 },
                    height: { ideal: 640 },
                    aspectRatio: 3/4
                }
            });
            selfieStream = stream;

            video.srcObject = stream;
            video.style.display = 'block';

            // Hide the placeholder and show video
            const placeholder = container.querySelector('.text-center');
            if (placeholder) {
                placeholder.style.display = 'none';
            }

            // Show capture button
            captureBtn.style.display = 'block';

            // Update status to show camera is ready
            statusElement.innerHTML = `
                <div class="location-info success">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-camera me-1"></i>
                        <div>
                            <strong>Kamera aktif</strong>
                            <br><small class="text-muted">Klik tombol "Ambil Foto" untuk mengambil selfie</small>
                        </div>
                    </div>
                </div>
            `;

        } catch (error) {
            console.error('Error accessing camera:', error);
            const statusElement = document.getElementById('selfie-status');
            if (statusElement) {
                statusElement.innerHTML = `
                    <div class="location-info error">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error-circle me-1"></i>
                            <div>
                                <strong>Kamera tidak dapat diakses</strong>
                                <br><small class="text-muted">Pastikan memberikan izin kamera</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            throw error; // Re-throw to handle in calling function
        }
    }

    // Capture selfie
    function captureSelfie() {
        const video = document.getElementById('selfie-video');
        const canvas = document.getElementById('selfie-canvas');
        const ctx = canvas.getContext('2d');

        // Set canvas to portrait dimensions (3:4 aspect ratio)
        const aspectRatio = 3/4;
        const canvasWidth = Math.min(video.videoWidth, video.videoHeight * aspectRatio);
        const canvasHeight = canvasWidth / aspectRatio;

        canvas.width = canvasWidth;
        canvas.height = canvasHeight;

    // Center the image on canvas for portrait crop
    const sourceX = (video.videoWidth - canvasWidth) / 2;
    const sourceY = (video.videoHeight - canvasHeight) / 2;

    // Balik horizontal sebelum menggambar agar hasil tidak terbalik
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, sourceX, sourceY, canvasWidth, canvasHeight, 0, 0, canvasWidth, canvasHeight);
    ctx.setTransform(1, 0, 0, 1, 0, 0); // reset transform setelah menggambar

        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        const selfieDataInput = document.getElementById('selfie-data');
        const selfiePreview = document.getElementById('selfie-preview');

        if (selfieDataInput) {
            selfieDataInput.value = imageData;
        }

        if (selfiePreview) {
            selfiePreview.src = imageData;
            selfiePreview.style.display = 'block';
        }

        // Hide video and show preview
        if (video) {
            video.style.display = 'none';
        }

        // Hide capture button and show retake button
        const captureBtn = document.getElementById('btn-capture-selfie');
        const retakeBtn = document.getElementById('btn-retake-selfie');

        if (captureBtn) {
            captureBtn.style.display = 'none';
        }

        if (retakeBtn) {
            retakeBtn.style.display = 'block';
        }

        // Stop camera stream
        if (selfieStream) {
            selfieStream.getTracks().forEach(track => track.stop());
            selfieStream = null;
        }

        selfieCaptured = true;
        document.getElementById('selfie-status').innerHTML = `
            <div class="location-info success">
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle me-1"></i>
                    <div>
                        <strong>Selfie berhasil diambil</strong>
                        <br><small class="text-muted">Klik tombol "Kirim Presensi" untuk menyelesaikan</small>
                    </div>
                </div>
            </div>
        `;

        // Show submit button after selfie is captured
        setTimeout(() => {
            // Verify selfie data is set before proceeding
            const selfieData = document.getElementById('selfie-data').value;
            console.log('Selfie captured with data length:', selfieData.length);

            if (selfieData && selfieData.length > 100) {
                // Show submit button and hide presensi button
                $('#btn-presensi').hide();
                $('#btn-submit-presensi').show();
                $('#btn-submit-presensi').prop('disabled', false);
            } else {
                console.error('Selfie data not properly set, length:', selfieData.length);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Foto selfie tidak berhasil diambil. Silakan coba lagi.',
                    confirmButtonText: 'Oke'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-camera me-1"></i>Ambil Selfie');
            }
        }, 1000);
    }

    // Retake selfie
    function retakeSelfie() {
        const selfiePreview = document.getElementById('selfie-preview');
        const selfieDataInput = document.getElementById('selfie-data');

        if (selfiePreview) {
            selfiePreview.style.display = 'none';
        }

        if (selfieDataInput) {
            selfieDataInput.value = '';
        }

        selfieCaptured = false;
        // Hide submit button and show presensi button again
        $('#btn-submit-presensi').hide();
        $('#btn-presensi').show();
        initializeSelfieCamera();
    }

    // Event listeners for selfie buttons
    const captureBtn = document.getElementById('btn-capture-selfie');
    const retakeBtn = document.getElementById('btn-retake-selfie');

    if (captureBtn) {
        captureBtn.addEventListener('click', captureSelfie);
    }

    if (retakeBtn) {
        retakeBtn.addEventListener('click', retakeSelfie);
    }

    // Handle presensi button (Ambil Selfie)
    $('#btn-presensi').click(async function() {
        // First, request camera access and show camera interface
        if (!selfieCaptured) {
            try {
                await initializeSelfieCamera();
                // Camera initialized, user can now click the capture button manually
                return;
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Dapat Diakses',
                    text: 'Tidak dapat mengakses kamera. Pastikan memberikan izin kamera dan coba lagi.',
                    confirmButtonText: 'Oke'
                });
                return;
            }
        }
    });

    // Handle submit presensi button
    $('#btn-submit-presensi').click(async function() {
        // If selfie is already captured, proceed with location validation
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Data lokasi belum lengkap. Pastikan GPS aktif dan tunggu proses pengumpulan data selesai.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        // Allow presensi even if location reading failed (single reading is enough)
        if (locationReadings.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Tidak dapat mendapatkan lokasi. Pastikan GPS aktif dan coba lagi.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get final location reading (button click) as reading4
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let reading4Lat = position.coords.latitude;
                let reading4Lng = position.coords.longitude;
                let reading4Timestamp = Date.now();

                // Build location readings array - include multiple readings for validation
                let allReadings = locationReadings.slice(); // Copy existing readings

                // Add final reading on button click
                allReadings.push({
                    latitude: reading4Lat,
                    longitude: reading4Lng,
                    timestamp: reading4Timestamp,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed
                });

                const selfieDataInput = document.getElementById('selfie-data');
                let selfieDataValue = selfieDataInput ? selfieDataInput.value : '';
                console.log('Final selfie data length:', selfieDataValue.length);
                console.log('Final selfie data starts with:', selfieDataValue.substring(0, 50));

                // Ensure selfie data is valid before sending
                if (!selfieDataValue || selfieDataValue.length < 100) {
                    console.error('Selfie data validation failed, length:', selfieDataValue.length);
                    $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Data foto selfie tidak valid. Silakan ambil foto lagi.',
                        confirmButtonText: 'Oke'
                    });
                    return;
                }

                let postData = {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    latitude: reading4Lat,
                    longitude: reading4Lng,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify(allReadings),
                    selfie_data: selfieDataValue
                };

                // Update UI with final location data
                $('#latitude').val(reading4Lat.toFixed(6));
                $('#longitude').val(reading4Lng.toFixed(6));

                // Update user location map with final position
                if (userLocationMap) {
                    updateUserLocationMap(reading4Lat, reading4Lng);
                }

                // Get address
                getAddressFromCoordinates(reading4Lat, reading4Lng);

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle text-success me-2"></i>
                            <div>
                                <strong class="small">Lokasi berhasil didapatkan!</strong>
                            </div>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("mobile.presensi.store") }}',
                    method: 'POST',
                    data: postData,
                    timeout: 30000,
                    success: function(resp) {
                        if (resp && resp.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: resp.message || 'Presensi berhasil dicatat',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Instead of full reload, update the UI dynamically
                                updatePresensiUI(resp);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: resp.message || 'Gagal melakukan presensi. Coba lagi.',
                            });
                            $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                        }
                    },
                    error: function(xhr, status, err) {
                        let message = 'Gagal menghubungi server.';
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: message
                        });
                        $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                    }
                });

            },
            function(err){
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan GPS',
                    text: err.message || 'Tidak dapat mengambil lokasi terakhir.'
                });
                $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 });
    });
});

// Function to update presensi UI after successful submission
function updatePresensiUI(resp) {
    // Update the status card to show presensi has been recorded
    const statusCardHtml = `
        <div class="status-card success">
            <div class="d-flex align-items-center">
                <div class="status-icon">
                    <i class="bx bx-check-circle"></i>
                </div>
                <div>
                    <h6 class="mb-1">Presensi Sudah Dicatat</h6>
                    <div class="mb-2" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 4px;">
                        <small class="text-white-50">${resp.madrasah_name || 'Madrasah'}</small>
                        <p class="mb-1">Masuk: <strong>${resp.waktu_masuk || new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}</strong></p>
                        <p class="mb-0 text-muted">Belum presensi keluar</p>
                    </div>
                    <p class="mb-0 text-muted">Lakukan presensi keluar jika sudah selesai.</p>
                </div>
            </div>
        </div>
    `;

    // Find and replace the status card
    const statusCardContainer = document.querySelector('.alert-custom.warning, .alert-custom.success, .status-card');
    if (statusCardContainer) {
        statusCardContainer.outerHTML = statusCardHtml;
    }

    // Update the presensi button to show "Presensi Keluar"
    const presensiBtn = document.getElementById('btn-presensi');
    if (presensiBtn) {
        presensiBtn.innerHTML = '<i class="bx bx-log-out-circle me-1"></i>Presensi Keluar';
        presensiBtn.disabled = false;
    }

    // Hide submit button
    const submitBtn = document.getElementById('btn-submit-presensi');
    if (submitBtn) {
        submitBtn.style.display = 'none';
    }

    // Reset selfie section for next use
    resetSelfieSection();

    // Update the header subtitle if needed
    const subtitle = document.querySelector('.presensi-header h5');
    if (subtitle && resp.madrasah_name) {
        subtitle.textContent = resp.madrasah_name;
    }
}

// Function to reset selfie section
function resetSelfieSection() {
    // Hide video and preview
    const video = document.getElementById('selfie-video');
    const preview = document.getElementById('selfie-preview');
    const canvas = document.getElementById('selfie-canvas');

    if (video) video.style.display = 'none';
    if (preview) preview.style.display = 'none';
    if (canvas) canvas.style.display = 'none';

    // Hide buttons
    const captureBtn = document.getElementById('btn-capture-selfie');
    const retakeBtn = document.getElementById('btn-retake-selfie');

    if (captureBtn) captureBtn.style.display = 'none';
    if (retakeBtn) retakeBtn.style.display = 'none';

    // Show placeholder
    const container = document.getElementById('selfie-container');
    if (container) {
        const placeholder = container.querySelector('.text-center');
        if (placeholder) {
            placeholder.style.display = 'block';
        }
    }

    // Reset status
    const statusElement = document.getElementById('selfie-status');
    if (statusElement) {
        statusElement.innerHTML = `
            <div class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-camera-off me-1"></i>
                    <div>
                        <strong>Selfie belum diambil</strong>
                        <br><small class="text-muted">Klik tombol presensi untuk mengaktifkan kamera</small>
                    </div>
                </div>
            </div>
        `;
    }

    // Clear selfie data
    const selfieDataInput = document.getElementById('selfie-data');
    if (selfieDataInput) {
        selfieDataInput.value = '';
    }

    // Reset flag
    selfieCaptured = false;

    // Stop any active camera stream
    if (selfieStream) {
        selfieStream.getTracks().forEach(track => track.stop());
        selfieStream = null;
    }
}

// Initialize map for kepala madrasah monitoring
// Note: This section is handled server-side in the Blade template
