let currentStep = 1;
const totalSteps = 4;

// Auto-save functionality
const AUTO_SAVE_KEY = 'talenta_draft';

// Function to save form data to localStorage
function saveFormData() {
    const formData = {};
    const form = document.querySelector('form');

    if (!form) return;

    // Get all form inputs, selects, and textareas, but exclude file inputs
    const inputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
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

        // Also save on change events for select elements, checkboxes, and file inputs
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

    // Setup file size validation
    setupFileSizeValidation();
}

// Function to setup file size validation
function setupFileSizeValidation() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateFileSize(this);
        });
    });
}

// Function to validate file size
function validateFileSize(input) {
    const maxSizeInMB = 10; // 10MB limit as per controller validation
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        if (file.size > maxSizeInBytes) {
            // Clear the file input
            input.value = '';

            // Show SweetAlert
            Swal.fire({
                title: 'File Terlalu Besar',
                text: `Ukuran file "${file.name}" melebihi batas maksimal ${maxSizeInMB}MB. Silakan pilih file dengan ukuran lebih kecil.`,
                icon: 'error',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545',
                customClass: {
                    popup: 'swal-mobile'
                }
            });

            return false;
        }
    }

    return true;
}

// Function to validate all file sizes before form submission
function validateAllFileSizes() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    const maxSizeInMB = 10; // 10MB limit as per controller validation
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

    for (let input of fileInputs) {
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            if (file.size > maxSizeInBytes) {
                Swal.fire({
                    title: 'File Terlalu Besar',
                    text: `Ukuran file "${file.name}" melebihi batas maksimal ${maxSizeInMB}MB. Silakan pilih file dengan ukuran lebih kecil.`,
                    icon: 'error',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc3545',
                    customClass: {
                        popup: 'swal-mobile'
                    }
                });

                // Focus on the problematic file input
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                input.focus();

                return false;
            }
        }
    }

    return true;
}

// Function to toggle TPT level content
function toggleTptLevel(level) {
    const content = document.getElementById(`tpt-level-${level}`);
    const icon = document.getElementById(`icon-${level}`);

    if (content && icon) {
        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }
}

// Function to toggle upload field visibility based on selection
function toggleUploadField(section) {
    const select = document.querySelector(`select[name="${section}_status"]`);
    const uploadDiv = document.getElementById(`${section}-upload`);
    const uploadInput = uploadDiv ? uploadDiv.querySelector('input[type="file"]') : null;

    if (select && uploadDiv) {
        if (select.value === 'sudah') {
            uploadDiv.style.display = 'block';
            if (uploadInput) {
                uploadInput.setAttribute('required', 'required');
            }
        } else {
            uploadDiv.style.display = 'none';
            if (uploadInput) {
                uploadInput.removeAttribute('required');
            }
        }
    }
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
});

// Submit as draft
function submitDraft() {
    // Validate file sizes before submission
    if (!validateAllFileSizes()) {
        return;
    }

    const formStatus = document.getElementById('form-status');
    if (formStatus) {
        formStatus.value = 'draft';
    }

    // Show loading indicator
    Swal.fire({
        title: 'Menyimpan Draft...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit the form
    const form = document.getElementById('talenta-form');
    if (form) {
        form.submit();
    }
}

// Submit and publish
function submitPublish() {
    // Validate file sizes before submission
    if (!validateAllFileSizes()) {
        return;
    }

    // Validate all required fields before publishing
    if (!validateAllSteps()) {
        Swal.fire({
            title: 'Validasi Gagal',
            text: 'Mohon lengkapi semua kolom yang wajib diisi sebelum mempublikasikan data talenta.',
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#004b4c',
            customClass: {
                popup: 'swal-mobile'
            }
        });
        return;
    }

    // Confirm before publishing
    Swal.fire({
        title: 'Publikasikan Data Talenta?',
        text: 'Setelah dipublikasikan, data tidak dapat diubah lagi. Apakah Anda yakin?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Publikasikan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#004b4c',
        cancelButtonColor: '#6c757d',
        customClass: {
            popup: 'swal-mobile'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formStatus = document.getElementById('form-status');
            if (formStatus) {
                formStatus.value = 'published';
            }

            // Show loading indicator
            Swal.fire({
                title: 'Mempublikasikan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            const form = document.getElementById('talenta-form');
            if (form) {
                form.submit();
            }
        }
    });
}

// Validate all steps before publishing
function validateAllSteps() {
    let isValid = true;

    // Clear previous validation messages
    document.querySelectorAll('.form-error').forEach(error => error.remove());

    for (let step = 1; step <= totalSteps; step++) {
        const currentStepElement = document.querySelector(`.step-content[data-step="${step}"]`);
        if (!currentStepElement) continue;

        const requiredFields = currentStepElement.querySelectorAll('input[required], textarea[required], select[required]');
        let firstInvalidField = null;

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
            }
        });

        // If validation failed, scroll to the step with error
        if (!isValid && firstInvalidField) {
            showStep(step);
            setTimeout(() => {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
            }, 100);
            break;
        }
    }

    return isValid;
}
