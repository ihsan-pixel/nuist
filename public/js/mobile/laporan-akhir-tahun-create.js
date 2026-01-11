let currentStep = 1;
const totalSteps = 8;

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

    // Update indicators
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNumber = index + 1;
        indicator.classList.remove('active', 'completed');

        if (stepNumber === step) {
            indicator.classList.add('active');
        } else if (stepNumber < step) {
            indicator.classList.add('completed');
        }
    });

    currentStep = step;
}

function nextStep() {
    if (currentStep < totalSteps) {
        showStep(currentStep + 1);
    }
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

// Initialize first step
document.addEventListener('DOMContentLoaded', function() {
    showStep(1);

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

    // Format Rupiah for dana fields
    const danaFields = [
        'target_dana',
        'capaian_dana',
        'target_dana_tahun_berikutnya'
    ];

    danaFields.forEach(fieldName => {
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

    // Format percentage for alumni fields
    const alumniFields = [
        'target_alumni',
        'capaian_alumni',
        'target_alumni_berikutnya'
    ];

    alumniFields.forEach(fieldName => {
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
        danaFields.forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            if (input && input.value.includes('Rp ')) {
                input.value = input.value.replace(/[^\d]/g, '');
            }
        });
        alumniFields.forEach(fieldName => {
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

    // Update breakdown display
    document.getElementById('skor_siswa_kategori').textContent = `Skor Kategori Siswa: ${skorSiswaKategori}`;
    document.getElementById('skor_siswa_prestasi').textContent = `Skor Prestasi Siswa: ${skorSiswaPrestasi}`;
    document.getElementById('skor_dana_kategori').textContent = `Skor Kategori Dana: ${skorDanaKategori}`;
    document.getElementById('skor_dana_prestasi').textContent = `Skor Prestasi Dana: ${skorDanaPrestasi}`;
    document.getElementById('skor_alumni_kategori').textContent = `Skor Kategori Alumni: ${skorAlumniKategori}`;
    document.getElementById('skor_alumni_prestasi').textContent = `Skor Prestasi Alumni: ${skorAlumniPrestasi}`;
    document.getElementById('skor_akreditasi').textContent = `Skor Akreditasi: ${skorAkreditasi}`;
    document.getElementById('total_breakdown').textContent = `Total: ${totalSkor}`;
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
