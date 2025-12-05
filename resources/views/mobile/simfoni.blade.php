@extends('layouts.mobile')

@section('title', 'Simfoni')
@section('subtitle', 'Submit Request')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb url('{{ asset("images/bg.png") }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .simfoni-container {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }

        .simfoni-form-group {
            margin-bottom: 14px;
        }

        .simfoni-form-group label {
            display: block;
            font-weight: 600;
            color: #6b4c9a;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .simfoni-form-group input[type="text"],
        .simfoni-form-group input[type="email"],
        .simfoni-form-group input[type="tel"],
        .simfoni-form-group textarea,
        .simfoni-form-group input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            transition: border-color 0.3s ease;
        }

        .simfoni-form-group input[type="text"]:focus,
        .simfoni-form-group input[type="email"]:focus,
        .simfoni-form-group input[type="tel"]:focus,
        .simfoni-form-group textarea:focus,
        .simfoni-form-group input[type="file"]:focus {
            outline: none;
            border-color: #6b4c9a;
            box-shadow: 0 0 0 3px rgba(107, 76, 154, 0.1);
        }

        .simfoni-form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-hint {
            font-size: 11px;
            color: #999;
            margin-top: 4px;
        }

        .form-error {
            color: #dc3545;
            font-size: 11px;
            margin-top: 4px;
        }

        .error-container {
            background: #fff5f5;
            border-left: 4px solid #dc3545;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-list li {
            color: #dc3545;
            font-size: 12px;
            padding: 2px 0;
        }

        .submit-btn {
            background: linear-gradient(135deg, #6b4c9a 0%, #5a4080 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #5a4080 0%, #4a3070 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 76, 154, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-upload-label {
            display: block;
            padding: 10px 12px;
            background: #f8f9fa;
            border: 2px dashed #6b4c9a;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            background: #f0ebf5;
            border-color: #5a4080;
        }

        .file-upload-label i {
            font-size: 16px;
            color: #6b4c9a;
            margin-right: 6px;
        }

        .file-upload-wrapper input[type="file"] {
            display: none;
        }

        .file-selected {
            font-size: 12px;
            color: #6b4c9a;
            margin-top: 4px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            color: #6b4c9a;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 12px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .back-button:hover {
            color: #5a4080;
        }

        .back-button i {
            font-size: 18px;
            margin-right: 6px;
        }

        .form-header {
            background: linear-gradient(135deg, #6b4c9a 0%, #5a4080 100%);
            color: white;
            padding: 16px;
            border-radius: 12px 12px 0 0;
            margin: -16px -16px 16px -16px;
            text-align: center;
        }

        .form-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
        }

        .form-header p {
            margin: 4px 0 0 0;
            font-size: 11px;
            opacity: 0.9;
        }
    </style>

    <!-- Back Button -->
    <button onclick="history.back()" class="back-button" style="margin-top: -10px;">
        <i class="bx bx-arrow-back"></i>
        <span>Kembali</span>
    </button>

    <!-- Form Container -->
    <div class="simfoni-container">
        <div class="form-header">
            <h4>Formulir Permintaan</h4>
            <p>Kami siap membantu Anda</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="error-container">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Start -->
        <form action="{{ route('mobile.simfoni.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- First Name -->
            <div class="simfoni-form-group">
                <label for="first_name">Nama Depan *</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    placeholder="Masukkan nama depan Anda"
                    value="{{ old('first_name', $user->first_name ?? '') }}"
                    required
                >
                @error('first_name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="simfoni-form-group">
                <label for="last_name">Nama Belakang *</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    placeholder="Masukkan nama belakang Anda"
                    value="{{ old('last_name', $user->last_name ?? '') }}"
                    required
                >
                @error('last_name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="simfoni-form-group">
                <label for="email">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Masukkan email Anda"
                    value="{{ old('email', $user->email ?? '') }}"
                    required
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mobile Number -->
            <div class="simfoni-form-group">
                <label for="mobile_number">Nomor Telepon *</label>
                <input 
                    type="tel" 
                    id="mobile_number" 
                    name="mobile_number" 
                    placeholder="Masukkan nomor telepon (10 digit)"
                    value="{{ old('mobile_number', $user->phone ?? '') }}"
                    required
                >
                @error('mobile_number')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Subject -->
            <div class="simfoni-form-group">
                <label for="subject">Subjek *</label>
                <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    placeholder="Masukkan subjek"
                    value="{{ old('subject') }}"
                    required
                >
                @error('subject')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Message -->
            <div class="simfoni-form-group">
                <label for="message">Pesan *</label>
                <textarea 
                    id="message" 
                    name="message" 
                    placeholder="Masukkan pesan Anda di sini (minimal 10 karakter)"
                    required
                >{{ old('message') }}</textarea>
                <div class="form-hint">Maksimal 5000 karakter</div>
                @error('message')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Attachments -->
            <div class="simfoni-form-group">
                <label>Lampiran</label>
                <div class="file-upload-wrapper">
                    <label for="attachments" class="file-upload-label">
                        <i class="bx bx-cloud-upload"></i>
                        <span>Pilih atau Drag & Drop file</span>
                    </label>
                    <input 
                        type="file" 
                        id="attachments" 
                        name="attachments"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    >
                    <div class="form-hint">
                        Format: PDF, DOC, DOCX, JPG, JPEG, PNG | Maksimal: 5MB
                    </div>
                    <div class="file-selected" id="fileSelected"></div>
                </div>
                @error('attachments')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="simfoni-form-group">
                <button type="submit" class="submit-btn">SUBMIT</button>
            </div>

            <!-- Info Text -->
            <div style="text-align: center; font-size: 11px; color: #999; margin-top: 12px;">
                <p style="margin: 0;">Silakan isi semua field yang bertanda *</p>
            </div>
        </form>
    </div>

    <script>
        // Handle file upload
        document.getElementById('attachments').addEventListener('change', function(e) {
            const fileSelected = document.getElementById('fileSelected');
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileSelected.innerHTML = `✓ ${fileName} (${fileSize} MB)`;
            } else {
                fileSelected.innerHTML = '';
            }
        });

        // Drag and drop functionality
        const fileInput = document.getElementById('attachments');
        const fileLabel = fileInput.parentElement.querySelector('.file-upload-label');

        fileLabel.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = '#f0ebf5';
            this.style.borderColor = '#5a4080';
        });

        fileLabel.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.background = '#f8f9fa';
            this.style.borderColor = '#6b4c9a';
        });

        fileLabel.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '#f8f9fa';
            this.style.borderColor = '#6b4c9a';
            if (e.dataTransfer.files) {
                fileInput.files = e.dataTransfer.files;
                // Trigger change event
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    </script>
</div>
@endsection
