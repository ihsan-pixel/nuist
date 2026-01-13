let currentStep = 1;
const totalSteps = 10;

// Auto-save functionality
const AUTO_SAVE_KEY = 'laporan_akhir_tahun_draft';

// Function to save form data to localStorage
function saveFormData() {
    const formData = {};
    const form = document.querySelector('form');

    if (!form) return;

    // Get all form inputs, selects, and textareas
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.name && input.name !== '_token') { // Exclude CSRF token
            if (input.type === 'checkbox') {
                formData[input.name] = input.checked;
            } else if (input.type === 'radio') {
                if (input.checked) {
                    formData[input.name] = input.value;
                }
            } else {
                formData[input.name] = input.value;
            }
        }
    });

    // Save to localStorage
    localStorage.setItem(AUTO_SAVE_KEY, JSON.stringify(formData));

    // Show auto-save indicator
    showAutoSaveIndicator();

    console.log('Form data auto-saved');
}

// Function to show auto-save indicator
function showAutoSaveIndicator() {
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'block';
        // Hide after 3 seconds
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 3000);
    }
}

// Function to load form data from localStorage
function loadFormData() {
    const savedData = localStorage.getItem(AUTO_SAVE_KEY);
    if (!savedData) return;

    try {
        const formData = JSON.parse(savedData);
        const form = document.querySelector('form');

        if (!form) return;

        // Populate form fields with saved data
        Object.keys(formData).forEach(name => {
            const inputs = form.querySelectorAll(`[name="${name}"]`);
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = formData[name];
                } else if (input.type === 'radio') {
                    if (input.value === formData[name]) {
                        input.checked = true;
                    }
                } else {
                    input.value = formData[name];
                }
            });
        });

        // After loading data, reinitialize talenta fields based on loaded jumlah_talenta
        initializeTalentaFields();

        // Re-populate talenta fields after they are regenerated
        setTimeout(() => {
            Object.keys(formData).forEach(name => {
                if (name.startsWith('nama_talenta[') || name.startsWith('alasan_talenta[')) {
                    const inputs = form.querySelectorAll(`[name="${name}"]`);
                    inputs.forEach(input => {
                        input.value = formData[name];
                    });
                }
            });
        }, 100);

        console.log('Form data loaded from auto-save');
    } catch (error) {
        console.error('Error loading saved form data:', error);
    }
}

// Function to clear saved form data
function clearSavedData() {
    localStorage.removeItem(AUTO_SAVE_KEY);
    console.log('Auto-saved data cleared');
}

// Function to set up auto-save on form changes
function setupAutoSave() {
    const form = document.querySelector('form');
    if (!form) return;

    // Auto-save on input changes (debounced)
    let saveTimeout;
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                saveFormData();
            }, 1000); // Save after 1 second of no typing
        });

        // Also save on change events for select elements and checkboxes
        input.addEventListener('change', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                saveFormData();
            }, 500); // Save after 0.5 seconds for change events
        });
    });

    // Save data immediately when user leaves the page
    window.addEventListener('beforeunload', function() {
        saveFormData();
    });

    // Clear saved data on successful form submission
    form.addEventListener('submit', function() {
        clearSavedData();
    });
}

// Navigation functions
function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.remove('active');
    });

    // Show current step
    const currentStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
    if (currentStepContent) {
        currentStepContent.classList.add('active');
    }

    // Update progress bar
    updateProgressBar(step);

    currentStep = step;

    // Initialize signature pad when step 10 is shown
    if (step === 10) {
        initializeSignaturePad();
    }
}

// Function to update progress bar
function updateProgressBar(step) {
    const progressFill = document.getElementById('progress-fill');
    const progressPercentage = document.getElementById('progress-percentage');
    const progressStep = document.getElementById('progress-step');

    if (progressFill && progressPercentage && progressStep) {
        const percentage = (step / totalSteps) * 100;
        progressFill.style.width = percentage + '%';
        progressPercentage.textContent = Math.round(percentage) + '%';
        progressStep.textContent = `Step ${step} dari ${totalSteps}`;
    }
}

function nextStep() {
    if (currentStep < totalSteps) {
        // Validate required fields in current step before proceeding
        if (validateCurrentStep()) {
            showStep(currentStep + 1);
        }
    }
}

// Function to validate required fields in current step
function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.step-content[data-step="${currentStep}"]`);
    if (!currentStepElement) return true;

    const requiredFields = currentStepElement.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    let firstInvalidField = null;

    // Clear previous validation messages
    currentStepElement.querySelectorAll('.form-error').forEach(error => error.remove());

    requiredFields.forEach(field => {
        let fieldValue = '';

        if (field.type === 'checkbox') {
            fieldValue = field.checked ? 'checked' : '';
        } else if (field.tagName === 'SELECT') {
            fieldValue = field.value.trim();
        } else {
            fieldValue = field.value.trim();
        }

        if (!fieldValue) {
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = field;
            }

            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'form-error';
            errorDiv.textContent = 'Kolom ini wajib diisi';

            // Insert error message after the field
            field.parentNode.insertBefore(errorDiv, field.nextSibling);

            // Add error styling to field
            field.style.borderColor = '#dc3545';
            field.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
        } else {
            // Remove error styling if field is valid
            field.style.borderColor = '#e9ecef';
            field.style.boxShadow = 'none';
        }
    });

    // Special validation for dynamic talenta fields in step 4
    if (currentStep === 4) {
        const jumlahTalenta = document.getElementById('jumlah_talenta');
        if (jumlahTalenta && jumlahTalenta.value) {
            const talentaCount = parseInt(jumlahTalenta.value);
            const talentaContainers = currentStepElement.querySelectorAll('.talenta-group');

            for (let i = 0; i < talentaCount; i++) {
                const container = talentaContainers[i];
                if (container) {
                    const namaInput = container.querySelector('input[name="nama_talenta[]"]');
                    const alasanTextarea = container.querySelector('textarea[name="alasan_talenta[]"]');

                    if (namaInput && !namaInput.value.trim()) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = namaInput;

                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'form-error';
                        errorDiv.textContent = 'Nama talenta wajib diisi';
                        namaInput.parentNode.insertBefore(errorDiv, namaInput.nextSibling);
                        namaInput.style.borderColor = '#dc3545';
                        namaInput.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                    }

                    if (alasanTextarea && !alasanTextarea.value.trim()) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = alasanTextarea;

                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'form-error';
                        errorDiv.textContent = 'Alasan talenta wajib diisi';
                        alasanTextarea.parentNode.insertBefore(errorDiv, alasanTextarea.nextSibling);
                        alasanTextarea.style.borderColor = '#dc3545';
                        alasanTextarea.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                    }
                }
            }
        }
    }

    // If validation failed, show SweetAlert and scroll to first invalid field
    if (!isValid) {
        Swal.fire({
            title: 'Kolom Wajib Belum Diisi',
            text: 'Mohon lengkapi semua kolom yang wajib diisi sebelum melanjutkan ke langkah berikutnya.',
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#004b4c',
            customClass: {
                popup: 'swal-mobile'
            }
        }).then(() => {
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
            }
        });

        return false;
    }

    return true;
}

function prevStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
}

// Title case for Nama Kepala Sekolah and Nama Madrasah
function toTitleCase(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}

const namaKepalaInput = document.querySelector('input[name="nama_kepala_sekolah"]');
if (namaKepalaInput) {
    namaKepalaInput.addEventListener('input', function() {
        this.value = toTitleCase(this.value);
    });
}

const namaMadrasahInput = document.querySelector('input[name="nama_madrasah"]');
if (namaMadrasahInput) {
    namaMadrasahInput.addEventListener('input', function() {
        this.value = toTitleCase(this.value);
    });
}

// Function to clear field validation errors in real-time
function clearFieldValidation(field) {
    // Remove error styling
    field.style.borderColor = '#e9ecef';
    field.style.boxShadow = 'none';

    // Remove error message if it exists
    const errorDiv = field.parentNode.querySelector('.form-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Function to validate single field
function validateSingleField(field) {
    let fieldValue = '';

    if (field.type === 'checkbox') {
        fieldValue = field.checked ? 'checked' : '';
    } else if (field.tagName === 'SELECT') {
        fieldValue = field.value.trim();
    } else {
        fieldValue = field.value.trim();
    }

    if (fieldValue) {
        // Field is now valid, clear any previous errors
        clearFieldValidation(field);
        return true;
    }

    return false;
}

// Function to setup real-time validation clearing
function setupRealTimeValidation() {
    const allSteps = document.querySelectorAll('.step-content');
    allSteps.forEach(step => {
        const requiredFields = step.querySelectorAll('input[required], textarea[required], select[required]');
        requiredFields.forEach(field => {
            // Clear validation errors as user types/selects
            field.addEventListener('input', function() {
                validateSingleField(this);
            });

            field.addEventListener('change', function() {
                validateSingleField(this);
            });

            // Special handling for checkboxes
            if (field.type === 'checkbox') {
                field.addEventListener('change', function() {
                    validateSingleField(this);
                });
            }
        });
    });
}

// Initialize first step and auto-save functionality
document.addEventListener('DOMContentLoaded', function() {
    showStep(1);

    // Load saved data on page load
    loadFormData();

    // Set up auto-save on form changes
    setupAutoSave();

    // Set up real-time validation clearing
    setupRealTimeValidation();

    // Toggle penjelasan functionality
    const togglePenjelasanBtn = document.getElementById('toggle_penjelasan');
    const penjelasanContent = document.getElementById('penjelasan_content');

    if (togglePenjelasanBtn && penjelasanContent) {
        // Initialize state
        let isVisible = false;
        penjelasanContent.style.display = 'none';
        togglePenjelasanBtn.innerHTML = '<i class="bx bx-info-circle" style="margin-right: 4px;"></i>Lihat Penjelasan';

        // Remove any existing event listeners and use direct assignment
        togglePenjelasanBtn.onclick = null;
        togglePenjelasanBtn.onclick = function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();

            if (!isVisible) {
                penjelasanContent.style.display = 'block';
                togglePenjelasanBtn.innerHTML = '<i class="bx bx-info-circle" style="margin-right: 4px;"></i>Sembunyikan Penjelasan';
                isVisible = true;
            } else {
                penjelasanContent.style.display = 'none';
                togglePenjelasanBtn.innerHTML = '<i class="bx bx-info-circle" style="margin-right: 4px;"></i>Lihat Penjelasan';
                isVisible = false;
            }

            return false;
        };
    }

    // Format Rupiah for currency fields in the form
    const currencyFields = [
        'bosnas_2023', 'bosnas_2024', 'bosnas_2025',
        'bosda_2023', 'bosda_2024', 'bosda_2025',
        'spp_bppp_lain_2023', 'spp_bppp_lain_2024', 'spp_bppp_lain_2025',
        'pendapatan_unit_usaha_2023', 'pendapatan_unit_usaha_2024', 'pendapatan_unit_usaha_2025'
    ];

    // Format percentage for alumni fields
    const percentageFields = [
        'persentase_alumni_bekerja', 'persentase_alumni_wirausaha', 'persentase_alumni_tidak_terdeteksi'
    ];

    currencyFields.forEach(fieldName => {
        const input = document.querySelector(`input[name="${fieldName}"]`);
        if (input) {
            // Format initial value if it exists
            if (input.value && !input.value.includes('Rp ')) {
                formatRupiah(input);
            }

            input.addEventListener('focus', function() {
                // Remove formatting when focused for editing
                let value = this.value.replace(/[^\d]/g, '');
                this.value = value;
            });
            input.addEventListener('blur', function() {
                // Format when leaving the field
                formatRupiah(this);
            });
            input.addEventListener('input', function() {
                // Allow free input while focused, only format on blur
                // This prevents interference during typing
            });
        }
    });

    percentageFields.forEach(fieldName => {
        const input = document.querySelector(`input[name="${fieldName}"]`);
        if (input) {
            // Format initial value if it exists
            if (input.value && !input.value.includes('%')) {
                formatPercentage(input);
            }

            input.addEventListener('focus', function() {
                // Remove formatting when focused for editing
                let value = this.value.replace(/[^\d]/g, '');
                this.value = value;
            });
            input.addEventListener('blur', function() {
                // Format when leaving the field
                formatPercentage(this);
            });
            input.addEventListener('input', function() {
                // Allow free input while focused, only format on blur
                // This prevents interference during typing
            });
        }
    });
});

// Format number to Rupiah
function formatRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
        input.value = 'Rp ' + value;
    } else {
        input.value = '';
    }
}

// Format number to percentage
function formatPercentage(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        input.value = value + '%';
    } else {
        input.value = '';
    }
}

// Remove Rupiah formatting for editing
function unformatRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    input.value = value;
}

// Before form submission, remove Rupiah and percentage formatting
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function() {
        currencyFields.forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            if (input && input.value.includes('Rp ')) {
                input.value = input.value.replace(/[^\d]/g, '');
            }
        });
        percentageFields.forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            if (input && input.value.includes('%')) {
                input.value = input.value.replace(/[^\d]/g, '');
            }
        });
    });
}

// Initialize existing dynamic fields from old input
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    // Initialize talenta fields based on jumlah_talenta
    initializeTalentaFields();

    // Dynamic inputs are now handled server-side with Blade templates
    // No additional JavaScript initialization needed

    // Initialize dynamic info for existing values
    updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info');
    updateDanaInfo('capaian_dana', 'capaian_dana_info');
    updateAlumniInfo('capaian_alumni', 'capaian_alumni_info');
    updateAkreditasiInfo();

    // Initialize total score
    updateTotalSkor();

    // Add event listeners for dynamic updates
    document.getElementById('capaian_jumlah_siswa').addEventListener('input', function() {
        updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info');
    });
    document.getElementById('target_jumlah_siswa').addEventListener('input', function() {
        updateTotalSkor(); // Update total score when target siswa changes
        updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info'); // Update info display
    });
    document.getElementById('capaian_alumni').addEventListener('input', function() {
        updateAlumniInfo('capaian_alumni', 'capaian_alumni_info');
    });
    document.getElementById('capaian_dana').addEventListener('blur', function() {
        updateDanaInfo('capaian_dana', 'capaian_dana_info');
    });
    document.getElementById('target_dana').addEventListener('blur', function() {
        updateTotalSkor(); // Update total score when target dana changes
        updateDanaInfo('capaian_dana', 'capaian_dana_info'); // Update info display
    });
    document.getElementById('target_dana').addEventListener('input', function() {
        updateDanaInfo('capaian_dana', 'capaian_dana_info'); // Update info display on input
    });
    document.getElementById('target_alumni').addEventListener('blur', function() {
        updateTotalSkor(); // Update total score when target alumni changes
        updateAlumniInfo('capaian_alumni', 'capaian_alumni_info'); // Update info display
    });
    document.getElementById('target_alumni').addEventListener('input', function() {
        updateAlumniInfo('capaian_alumni', 'capaian_alumni_info'); // Update info display on input
    });
    document.getElementById('akreditasi').addEventListener('change', updateAkreditasiInfo);
});

// Function to update student count info
function updateSiswaInfo(inputId, infoId) {
    const input = document.getElementById(inputId);
    const info = document.getElementById(infoId);
    const value = parseInt(input.value) || 0;

    let skorKategori = 1;
    let kategori = 'Posisi Zero';

    if (value > 1001) {
        skorKategori = 9;
        kategori = 'Unggulan A';
    } else if (value >= 751) {
        skorKategori = 8;
        kategori = 'Unggulan B';
    } else if (value >= 501) {
        skorKategori = 7;
        kategori = 'Mandiri A';
    } else if (value >= 251) {
        skorKategori = 6;
        kategori = 'Mandiri B';
    } else if (value >= 151) {
        skorKategori = 5;
        kategori = 'Pramandiri A';
    } else if (value >= 101) {
        skorKategori = 4;
        kategori = 'Pramandiri B';
    } else if (value >= 61) {
        skorKategori = 3;
        kategori = 'Rintisan A';
    } else if (value >= 20) {
        skorKategori = 2;
        kategori = 'Rintisan B';
    }

    // Calculate prestasi score
    const targetSiswaInput = document.getElementById('target_jumlah_siswa');
    let skorPrestasi = 0;
    let prestasiText = '';
    if (targetSiswaInput) {
        const target = parseInt(targetSiswaInput.value) || 0;
        if (value > target) {
            skorPrestasi = 2;
            prestasiText = ' (Prestasi: +2 - Melebihi Target)';
        } else if (value === target && value > 0) {
            skorPrestasi = 1;
            prestasiText = ' (Prestasi: +1 - Sesuai Target)';
        } else if (value < target && value > 0) {
            skorPrestasi = 0;
            prestasiText = ' (Prestasi: +0 - Di Bawah Target)';
        }
    }

    if (value > 0) {
        info.textContent = `Skor Kategori: ${skorKategori}, Kategori: ${kategori}${prestasiText}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }

    // Update total score when siswa info changes
    updateTotalSkor();
}

// Function to update alumni percentage info
function updateAlumniInfo(inputId, infoId) {
    const input = document.getElementById(inputId);
    const info = document.getElementById(infoId);
    const value = parseInt(input.value.replace(/[^\d]/g, '')) || 0;

    let skorKategori = 2;
    let kategori = 'Rintisan B';

    if (value >= 81) {
        skorKategori = 9;
        kategori = 'Unggulan A';
    } else if (value >= 66) {
        skorKategori = 8;
        kategori = 'Unggulan B';
    } else if (value >= 51) {
        skorKategori = 7;
        kategori = 'Mandiri A';
    } else if (value >= 35) {
        skorKategori = 6;
        kategori = 'Mandiri B';
    } else if (value >= 20) {
        skorKategori = 5;
        kategori = 'Pramandiri A';
    } else if (value >= 10) {
        skorKategori = 4;
        kategori = 'Pramandiri B';
    } else if (value >= 3) {
        skorKategori = 3;
        kategori = 'Rintisan A';
    }

    // Calculate prestasi score
    const targetAlumniInput = document.getElementById('target_alumni');
    let skorPrestasi = 0;
    let prestasiText = '';
    if (targetAlumniInput) {
        const target = parseInt(targetAlumniInput.value.replace(/[^\d]/g, '')) || 0;
        if (value > target) {
            skorPrestasi = 2;
            prestasiText = ' (Prestasi: +2 - Melebihi Target)';
        } else if (value === target && value > 0) {
            skorPrestasi = 1;
            prestasiText = ' (Prestasi: +1 - Sesuai Target)';
        } else if (value < target && value > 0) {
            skorPrestasi = 0;
            prestasiText = ' (Prestasi: +0 - Di Bawah Target)';
        }
    }

    if (value > 0) {
        info.textContent = `Skor Kategori: ${skorKategori}, Kategori: ${kategori}${prestasiText}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }

    // Update total score when alumni info changes
    updateTotalSkor();
}

// Function to update dana info
function updateDanaInfo(inputId, infoId) {
    const input = document.getElementById(inputId);
    const info = document.getElementById(infoId);
    const rawValue = parseInt(input.value.replace(/[^\d]/g, '')) || 0;
    const value = Math.floor(rawValue / 1000000); // Convert to millions

    let skorKategori = 1;
    let kategori = 'Posisi Zero';

    if (value > 5001) {
        skorKategori = 9;
        kategori = 'Unggulan A';
    } else if (value >= 3001) {
        skorKategori = 8;
        kategori = 'Unggulan B';
    } else if (value >= 2000) {
        skorKategori = 7;
        kategori = 'Mandiri A';
    } else if (value >= 1251) {
        skorKategori = 6;
        kategori = 'Mandiri B';
    } else if (value >= 751) {
        skorKategori = 5;
        kategori = 'Pramandiri A';
    } else if (value >= 351) {
        skorKategori = 4;
        kategori = 'Pramandiri B';
    } else if (value >= 151) {
        skorKategori = 3;
        kategori = 'Rintisan A';
    } else if (value >= 30) {
        skorKategori = 2;
        kategori = 'Rintisan B';
    }

    // Calculate prestasi score
    const targetDanaInput = document.getElementById('target_dana');
    let skorPrestasi = 0;
    let prestasiText = '';
    if (targetDanaInput) {
        const targetDana = parseInt(targetDanaInput.value.replace(/[^\d]/g, '')) || 0;
        if (rawValue > targetDana) {
            skorPrestasi = 2;
            prestasiText = ' (Prestasi: +2 - Melebihi Target)';
        } else if (rawValue === targetDana) {
            skorPrestasi = 1;
            prestasiText = ' (Prestasi: +1 - Sesuai Target)';
        } else if (rawValue < targetDana && rawValue > 0) {
            skorPrestasi = 0;
            prestasiText = ' (Prestasi: +0 - Di Bawah Target)';
        }
    }

    if (rawValue > 0) {
        info.textContent = `Skor Kategori: ${skorKategori}, Kategori: ${kategori}${prestasiText}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }

    // Update total score when dana info changes
    updateTotalSkor();
}

// Function to update accreditation info
function updateAkreditasiInfo() {
    const select = document.getElementById('akreditasi');
    const info = document.getElementById('akreditasi_info');
    const value = select.value;

    let skor = 1; // Default for "Belum"
    let kategori = 'Belum';

    if (value === 'A') {
        skor = 10;
        kategori = 'Unggulan A';
    } else if (value === 'B') {
        skor = 7;
        kategori = 'Mandiri A';
    } else if (value === 'C') {
        skor = 4;
        kategori = 'Rintisan A';
    }

    if (value) {
        info.textContent = `Skor: ${skor}, Kategori: ${kategori}`;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }

    // Update total score when akreditasi info changes
    updateTotalSkor();
}

// Function to calculate and update total score
function updateTotalSkor() {
    let totalSkor = 0;
    let skorSiswaKategori = 0;
    let skorSiswaPrestasi = 0;
    let skorDanaKategori = 0;
    let skorDanaPrestasi = 0;
    let skorAlumniKategori = 0;
    let skorAlumniPrestasi = 0;
    let skorAkreditasi = 0;

    // Get siswa kategori score (based on capaian jumlah siswa)
    const siswaInput = document.getElementById('capaian_jumlah_siswa');
    if (siswaInput) {
        const siswaValue = parseInt(siswaInput.value) || 0;
        if (siswaValue > 1001) skorSiswaKategori = 9;
        else if (siswaValue >= 751) skorSiswaKategori = 8;
        else if (siswaValue >= 501) skorSiswaKategori = 7;
        else if (siswaValue >= 251) skorSiswaKategori = 6;
        else if (siswaValue >= 151) skorSiswaKategori = 5;
        else if (siswaValue >= 101) skorSiswaKategori = 4;
        else if (siswaValue >= 61) skorSiswaKategori = 3;
        else if (siswaValue >= 20) skorSiswaKategori = 2;
        else if (siswaValue > 0) skorSiswaKategori = 1;
        totalSkor += skorSiswaKategori;
    }

    // Get siswa prestasi score (based on comparison with target)
    const targetSiswaInput = document.getElementById('target_jumlah_siswa');
    if (siswaInput && targetSiswaInput) {
        const capaian = parseInt(siswaInput.value) || 0;
        const target = parseInt(targetSiswaInput.value) || 0;
        if (capaian > target) skorSiswaPrestasi = 2;
        else if (capaian === target && capaian > 0) skorSiswaPrestasi = 1;
        else skorSiswaPrestasi = 0;
        totalSkor += skorSiswaPrestasi;
    }

    // Get dana kategori score (based on capaian dana)
    const danaInput = document.getElementById('capaian_dana');
    if (danaInput) {
        const danaRawValue = parseInt(danaInput.value.replace(/[^\d]/g, '')) || 0;
        const danaValue = Math.floor(danaRawValue / 1000000); // Convert to millions
        if (danaValue > 5001) skorDanaKategori = 9;
        else if (danaValue >= 3001) skorDanaKategori = 8;
        else if (danaValue >= 2000) skorDanaKategori = 7;
        else if (danaValue >= 1251) skorDanaKategori = 6;
        else if (danaValue >= 751) skorDanaKategori = 5;
        else if (danaValue >= 351) skorDanaKategori = 4;
        else if (danaValue >= 151) skorDanaKategori = 3;
        else if (danaValue >= 30) skorDanaKategori = 2;
        else if (danaRawValue > 0) skorDanaKategori = 1;
        totalSkor += skorDanaKategori;
    }

    // Get dana prestasi score (based on comparison with target)
    const targetDanaInput = document.getElementById('target_dana');
    if (danaInput && targetDanaInput) {
        const capaianDana = parseInt(danaInput.value.replace(/[^\d]/g, '')) || 0;
        const targetDana = parseInt(targetDanaInput.value.replace(/[^\d]/g, '')) || 0;
        if (capaianDana > targetDana) skorDanaPrestasi = 2;
        else if (capaianDana === targetDana) skorDanaPrestasi = 1;
        else skorDanaPrestasi = 0;
        totalSkor += skorDanaPrestasi;
    }

    // Get alumni kategori score (based on capaian alumni)
    const alumniInput = document.getElementById('capaian_alumni');
    if (alumniInput) {
        const alumniValue = parseInt(alumniInput.value.replace(/[^\d]/g, '')) || 0;
        if (alumniValue >= 81) skorAlumniKategori = 9;
        else if (alumniValue >= 66) skorAlumniKategori = 8;
        else if (alumniValue >= 51) skorAlumniKategori = 7;
        else if (alumniValue >= 35) skorAlumniKategori = 6;
        else if (alumniValue >= 20) skorAlumniKategori = 5;
        else if (alumniValue >= 10) skorAlumniKategori = 4;
        else if (alumniValue >= 3) skorAlumniKategori = 3;
        else if (alumniValue >= 1) skorAlumniKategori = 2;
        totalSkor += skorAlumniKategori;
    }

    // Get alumni prestasi score (based on comparison with target)
    const targetAlumniInput = document.getElementById('target_alumni');
    if (alumniInput && targetAlumniInput) {
        const capaian = parseInt(alumniInput.value.replace(/[^\d]/g, '')) || 0;
        const target = parseInt(targetAlumniInput.value.replace(/[^\d]/g, '')) || 0;
        if (capaian > target) skorAlumniPrestasi = 2;
        else if (capaian === target && capaian > 0) skorAlumniPrestasi = 1;
        else skorAlumniPrestasi = 0;
        totalSkor += skorAlumniPrestasi;
    }

    // Get akreditasi score
    const akreditasiSelect = document.getElementById('akreditasi');
    if (akreditasiSelect) {
        const akreditasiValue = akreditasiSelect.value;
        if (akreditasiValue === 'A') skorAkreditasi = 10;
        else if (akreditasiValue === 'B') skorAkreditasi = 7;
        else if (akreditasiValue === 'C') skorAkreditasi = 4;
        else if (akreditasiValue === 'Belum') skorAkreditasi = 1;
        totalSkor += skorAkreditasi;
    }

    // Update total score field
    const totalSkorField = document.getElementById('total_skor');
    if (totalSkorField) {
        totalSkorField.value = totalSkor;
    }

    // Update total score info based on scoring guidelines
    const totalSkorInfo = document.getElementById('total_skor_info');
    if (totalSkorInfo) {
        let kategori = '';
        if (totalSkor >= 0 && totalSkor <= 5) {
            kategori = 'Sangat Lemah';
        } else if (totalSkor >= 6 && totalSkor <= 16) {
            kategori = 'Lemah';
        } else if (totalSkor >= 17 && totalSkor <= 25) {
            kategori = 'Rintisan';
        } else if (totalSkor >= 26 && totalSkor <= 33) {
            kategori = 'Cukup';
        } else if (totalSkor >= 34 && totalSkor <= 38) {
            kategori = 'Mandiri (Kuat)';
        } else if (totalSkor >= 39 && totalSkor <= 43) {
            kategori = 'Unggul';
        } else {
            kategori = 'Tidak Valid';
        }

        totalSkorInfo.textContent = `Kategori: ${kategori}`;
        totalSkorInfo.style.display = 'block';
    }

    // Update breakdown display (with null checks)
    const skorSiswaKategoriEl = document.getElementById('skor_siswa_kategori');
    if (skorSiswaKategoriEl) skorSiswaKategoriEl.textContent = `Skor Kategori Siswa: ${skorSiswaKategori}`;

    const skorSiswaPrestasiEl = document.getElementById('skor_siswa_prestasi');
    if (skorSiswaPrestasiEl) skorSiswaPrestasiEl.textContent = `Skor Prestasi Siswa: ${skorSiswaPrestasi}`;

    const skorDanaKategoriEl = document.getElementById('skor_dana_kategori');
    if (skorDanaKategoriEl) skorDanaKategoriEl.textContent = `Skor Kategori Dana: ${skorDanaKategori}`;

    const skorDanaPrestasiEl = document.getElementById('skor_dana_prestasi');
    if (skorDanaPrestasiEl) skorDanaPrestasiEl.textContent = `Skor Prestasi Dana: ${skorDanaPrestasi}`;

    const skorAlumniKategoriEl = document.getElementById('skor_alumni_kategori');
    if (skorAlumniKategoriEl) skorAlumniKategoriEl.textContent = `Skor Kategori Alumni: ${skorAlumniKategori}`;

    const skorAlumniPrestasiEl = document.getElementById('skor_alumni_prestasi');
    if (skorAlumniPrestasiEl) skorAlumniPrestasiEl.textContent = `Skor Prestasi Alumni: ${skorAlumniPrestasi}`;

    const skorAkreditasiEl = document.getElementById('skor_akreditasi');
    if (skorAkreditasiEl) skorAkreditasiEl.textContent = `Skor Akreditasi: ${skorAkreditasi}`;

    const totalBreakdownEl = document.getElementById('total_breakdown');
    if (totalBreakdownEl) totalBreakdownEl.textContent = `Total: ${totalSkor}`;
}

// Dynamic input fields functionality
function addInputField(category) {
    const container = document.querySelector(`.dynamic-inputs[data-category="${category}"]`);
    if (!container) return;

    const inputRows = container.querySelectorAll('.input-row');
    const newIndex = inputRows.length;

    const newRow = document.createElement('div');
    newRow.className = 'input-row';

    const input = document.createElement('input');
    input.type = 'text';
    input.name = `upaya_${getFieldName(category)}[]`;
    input.placeholder = getPlaceholderText(category);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-input-btn';
    removeBtn.innerHTML = '<i class="bx bx-minus"></i>';
    removeBtn.onclick = function() {
        removeInputField(this);
    };

    newRow.appendChild(input);
    newRow.appendChild(removeBtn);

    container.appendChild(newRow);
}

function removeInputField(button) {
    const inputRow = button.closest('.input-row');
    const container = inputRow.closest('.dynamic-inputs');
    const inputRows = container.querySelectorAll('.input-row');

    // Keep at least one input field
    if (inputRows.length > 1) {
        inputRow.remove();
    }
}

function getFieldName(category) {
    switch(category) {
        case 'siswa': return 'capaian_siswa';
        case 'dana': return 'capaian_dana';
        case 'alumni': return 'alumni_bmwa';
        case 'akreditasi': return 'akreditasi';
        default: return category;
    }
}

function getPlaceholderText(category) {
    switch(category) {
        case 'siswa': return 'Upaya untuk mencapai target siswa';
        case 'dana': return 'Upaya untuk mencapai target dana';
        case 'alumni': return 'Upaya untuk alumni BMWA';
        case 'akreditasi': return 'Upaya untuk akreditasi';
        default: return 'Upaya';
    }
}

// Function to initialize talenta fields based on jumlah_talenta
function initializeTalentaFields() {
    const jumlahTalentaInput = document.getElementById('jumlah_talenta');
    const talentaContainer = document.querySelector('.dynamic-inputs[data-category="talenta"]');

    if (!jumlahTalentaInput || !talentaContainer) return;

    // Set default value to 3 if not set
    if (!jumlahTalentaInput.value) {
        jumlahTalentaInput.value = 3;
    }

    // Function to update talenta fields
    function updateTalentaFields() {
        const jumlah = parseInt(jumlahTalentaInput.value) || 3;
        if (jumlah < 3 || jumlah > 9) {
            jumlahTalentaInput.value = 3; // Reset to minimum if invalid
            return;
        }

        // Clear existing fields
        talentaContainer.innerHTML = '';

        // Generate new fields
        for (let i = 0; i < jumlah; i++) {
            const talentaItem = document.createElement('div');
            talentaItem.className = 'talenta-item';
            talentaItem.setAttribute('data-index', i);

            const row2Col = document.createElement('div');
            row2Col.className = 'row-2col';

            // Nama dropdown
            const namaGroup = document.createElement('div');
            namaGroup.className = 'form-group required';

            const namaLabel = document.createElement('label');
            namaLabel.textContent = `Nama Talenta ${i + 1}`;

            const namaSelect = document.createElement('select');
            namaSelect.name = `nama_talenta[${i}]`;
            namaSelect.required = true;

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Pilih Nama';
            namaSelect.appendChild(defaultOption);

            if (window.guruKaryawanData && Array.isArray(window.guruKaryawanData)) {
                window.guruKaryawanData.forEach(guru => {
                    const option = document.createElement('option');
                    option.value = guru.name;
                    option.textContent = guru.name;
                    namaSelect.appendChild(option);
                });
            }

            namaGroup.appendChild(namaLabel);
            namaGroup.appendChild(namaSelect);

            // Alasan input
            const alasanGroup = document.createElement('div');
            alasanGroup.className = 'form-group required';

            const alasanLabel = document.createElement('label');
            alasanLabel.textContent = `Alasan ${i + 1}`;

            const alasanInput = document.createElement('input');
            alasanInput.type = 'text';
            alasanInput.name = `alasan_talenta[${i}]`;
            alasanInput.placeholder = 'Alasan...';
            alasanInput.required = true;

            alasanGroup.appendChild(alasanLabel);
            alasanGroup.appendChild(alasanInput);

            row2Col.appendChild(namaGroup);
            row2Col.appendChild(alasanGroup);

            talentaItem.appendChild(row2Col);
            talentaContainer.appendChild(talentaItem);
        }
    }

    // Initial update
    updateTalentaFields();

    // Update on change
    jumlahTalentaInput.addEventListener('input', updateTalentaFields);
    jumlahTalentaInput.addEventListener('change', updateTalentaFields);
}

// Function to calculate total guru dan karyawan
function updateTotalGuruKaryawan() {
    const guruFields = [
        'pns_sertifikasi',
        'pns_non_sertifikasi',
        'gty_sertifikasi_inpassing',
        'gty_sertifikasi',
        'gty_non_sertifikasi',
        'gtt',
        'pty',
        'ptt'
    ];

    let total = 0;
    guruFields.forEach(fieldName => {
        const input = document.querySelector(`input[name="${fieldName}"]`);
        if (input) {
            const value = parseInt(input.value) || 0;
            total += value;
        }
    });

    const totalField = document.getElementById('total_guru_karyawan');
    if (totalField) {
        totalField.value = total;
    }
}

// Function to update upaya score
function updateUpayaScore() {
    const upayaRows = document.querySelectorAll('.upaya-row');
    let totalUpayaScore = 0;

    upayaRows.forEach(row => {
        const input = row.querySelector('input');
        const scoreValue = row.querySelector('.score-value');
        const value = input.value.trim();

        if (value.length > 0) {
            scoreValue.textContent = '1';
            totalUpayaScore += 1;
        } else {
            scoreValue.textContent = '0';
        }
    });

    // Update total skor upaya field
    const totalSkorUpayaField = document.getElementById('total_skor_upaya');
    if (totalSkorUpayaField) {
        totalSkorUpayaField.value = totalUpayaScore;
    }

    // Update total skor upaya info based on scoring guidelines
    const totalSkorUpayaInfo = document.getElementById('total_skor_upaya_info');
    if (totalSkorUpayaInfo) {
        let kategori = '';
        if (totalUpayaScore >= 0 && totalUpayaScore <= 2) {
            kategori = 'Sangat lemah';
        } else if (totalUpayaScore >= 3 && totalUpayaScore <= 7) {
            kategori = 'Lemah';
        } else if (totalUpayaScore >= 8 && totalUpayaScore <= 11) {
            kategori = 'Rintisan';
        } else if (totalUpayaScore >= 12 && totalUpayaScore <= 15) {
            kategori = 'Cukup';
        } else if (totalUpayaScore >= 16 && totalUpayaScore <= 18) {
            kategori = 'Mandiri (kuat)';
        } else if (totalUpayaScore >= 19 && totalUpayaScore <= 20) {
            kategori = 'Unggul';
        } else {
            kategori = 'Tidak Valid';
        }

        totalSkorUpayaInfo.textContent = `Kategori: ${kategori}`;
        totalSkorUpayaInfo.style.display = 'block';
    }
}

// Initialize guru karyawan total calculation
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    // Add event listeners for guru karyawan fields
    const guruFields = [
        'pns_sertifikasi',
        'pns_non_sertifikasi',
        'gty_sertifikasi_inpassing',
        'gty_sertifikasi',
        'gty_non_sertifikasi',
        'gtt',
        'pty',
        'ptt'
    ];

    guruFields.forEach(fieldName => {
        const input = document.querySelector(`input[name="${fieldName}"]`);
        if (input) {
            input.addEventListener('input', updateTotalGuruKaryawan);
        }
    });

    // Initialize total on page load
    updateTotalGuruKaryawan();
});

// Initialize signature pad functionality
function initializeSignaturePad() {
    const canvas = document.getElementById('signature-canvas');
    const clearBtn = document.getElementById('clear-signature');
    const signatureDataInput = document.getElementById('signature-data');

    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Set canvas background to white
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Set drawing properties
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Touch events for mobile
    canvas.addEventListener('touchstart', handleTouchStart);
    canvas.addEventListener('touchmove', handleTouchMove);
    canvas.addEventListener('touchend', stopDrawing);

    // Clear signature - remove any existing listeners first
    if (clearBtn) {
        // Remove existing event listeners to prevent duplicates
        clearBtn.onclick = null;
        clearBtn.removeEventListener('click', clearSignature);

        // Add single event listener
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            // Clear signature immediately without any loading state
            clearSignature();
        }, { once: false });
    }

    function startDrawing(e) {
        isDrawing = true;
        [lastX, lastY] = getMousePos(canvas, e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();

        const [x, y] = getMousePos(canvas, e);
        ctx.lineTo(x, y);
        ctx.stroke();

        [lastX, lastY] = [x, y];
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            // Save signature data
            saveSignature();
        }
    }

    function handleTouchStart(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }

    function handleTouchMove(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }

    function getMousePos(canvas, e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        return [
            (e.clientX - rect.left) * scaleX,
            (e.clientY - rect.top) * scaleY
        ];
    }

    function clearSignature() {
        // Add confirmation before clearing using SweetAlert
        Swal.fire({
            title: 'Hapus Tanda Tangan',
            text: 'Apakah Anda yakin ingin menghapus tanda tangan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            customClass: {
                popup: 'swal-mobile'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Clear canvas immediately using clearRect for better performance
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Reset canvas background
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Clear signature data input
                if (signatureDataInput) {
                    signatureDataInput.value = '';
                }

                // Reset drawing state
                isDrawing = false;
                lastX = 0;
                lastY = 0;

                // Reset canvas path
                ctx.beginPath();
            }
        });
    }

    function saveSignature() {
        if (signatureDataInput) {
            const dataURL = canvas.toDataURL('image/png');
            signatureDataInput.value = dataURL;
        }
    }

    // Make canvas responsive
    function resizeCanvas() {
        const container = canvas.parentElement;
        const containerWidth = container.offsetWidth;
        const aspectRatio = canvas.width / canvas.height;

        canvas.style.width = Math.min(containerWidth, 300) + 'px';
        canvas.style.height = (Math.min(containerWidth, 300) / aspectRatio) + 'px';
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
}

// Initialize signature pad on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeSignaturePad();
});
