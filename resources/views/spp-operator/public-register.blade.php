@extends('layouts.master-without-nav')

@section('title')
Pendaftaran Operator SPP
@endsection

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')
    <body>
@endsection

@section('content')
<div class="spp-register-shell">
    <div class="spp-register-grid">
        <section class="spp-register-hero">
            <div class="spp-register-badge">Pendaftaran</div>
            <h1>Pendaftaran Operator SPP Sekolah</h1>
            <p>Ajukan akun <strong>Operator SPP</strong> untuk sekolah Anda. Setiap sekolah hanya dapat mengajukan satu permohonan pendaftaran.</p>

            <div class="spp-register-points">
                <div class="point-card">
                    <strong>1 Permohonan per Sekolah</strong>
                    <span>Sekolah yang sudah pernah mendaftar tidak dapat mengirim pendaftaran ulang.</span>
                </div>
                <div class="point-card">
                    <strong>Email Aktif</strong>
                    <span>Email pendaftar wajib belum pernah dipakai di sistem dan akan menjadi email login.</span>
                </div>
                <div class="point-card">
                    <strong>Approval Operator Nuist</strong>
                    <span>Akun dibuat otomatis setelah disetujui, lalu password dikirim ke email pendaftar.</span>
                </div>
            </div>
        </section>

        <section class="spp-register-card">
            <div class="card-top">
                <img src="{{ asset('images/logo1.png') }}" alt="NUist" class="brand-logo">
                <div>
                    <h2>Form Operator SPP</h2>
                    <p>Lengkapi data akun Operator SPP Sekolah.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('spp-operator.register.store') }}" class="spp-register-form">
                @csrf
                <div class="form-block">
                    <label for="scod">SCOD Sekolah</label>
                    <input id="scod" type="text" name="scod" class="form-control" value="{{ old('scod') }}" placeholder="Ketik SCOD sekolah" required>
                </div>

                <div class="form-block">
                    <label for="madrasah_name">Sekolah</label>
                    <input
                        id="madrasah_name"
                        type="text"
                        class="form-control"
                        value="{{ $matchedMadrasah?->name }}"
                        placeholder="Nama sekolah akan terisi otomatis"
                        readonly
                    >
                    <div id="scodHelp" class="field-help">
                        @if($matchedMadrasah)
                            {{ $matchedMadrasah->kabupaten ? $matchedMadrasah->kabupaten . ' • ' : '' }}SCOD terdeteksi
                        @else
                            {{-- Ketik SCOD sekolah untuk memunculkan nama sekolah otomatis. --}}
                        @endif
                    </div>
                </div>

                <div class="form-block">
                    <label for="name">Nama User Operator SPP Sekolah</label>
                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-block">
                    <label for="jabatan">Jabatan</label>
                    <input id="jabatan" type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" required>
                </div>

                <div class="form-block">
                    <label for="email">Email Aktif</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-block">
                    <label for="no_hp">No. HP</label>
                    <input id="no_hp" type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                </div>

                <div class="notice-box">
                    <strong>Catatan</strong>
                    <span>Status akun akan direview oleh Super Admin. Password tidak diisi manual karena akan dibuat otomatis saat approval.</span>
                </div>

                <button type="submit" class="submit-btn">Kirim Pendaftaran</button>
                <a href="{{ route('login.operator-spp') }}" class="back-link">Sudah punya akun? Login Operator SPP</a>
            </form>
        </section>
    </div>
</div>

<style>
    body {
        margin: 0;
        background:
            radial-gradient(circle at top left, rgba(20, 83, 45, 0.16), transparent 34%),
            radial-gradient(circle at bottom right, rgba(15, 118, 110, 0.18), transparent 36%),
            linear-gradient(180deg, #f7faf9 0%, #eef4f2 100%);
        font-family: 'Poppins', sans-serif;
        color: #12312b;
    }

    .spp-register-shell {
        min-height: 100vh;
        padding: 36px 20px;
    }

    .spp-register-grid {
        max-width: 1180px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 28px;
        align-items: stretch;
    }

    .spp-register-hero,
    .spp-register-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 28px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
    }

    .spp-register-hero {
        padding: 38px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .spp-register-badge {
        display: inline-flex;
        width: fit-content;
        padding: 8px 14px;
        border-radius: 999px;
        background: #dff7ec;
        color: #166534;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .spp-register-hero h1 {
        font-size: 42px;
        line-height: 1.08;
        margin: 0 0 16px;
    }

    .spp-register-hero p {
        font-size: 16px;
        color: #4b635d;
        margin: 0 0 28px;
        max-width: 620px;
    }

    .spp-register-points {
        display: grid;
        gap: 14px;
    }

    .point-card {
        padding: 18px 20px;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f6fbf9 100%);
        border: 1px solid #dbe8e3;
    }

    .point-card strong,
    .point-card span {
        display: block;
    }

    .point-card strong {
        font-size: 15px;
        margin-bottom: 6px;
    }

    .point-card span {
        color: #5b706a;
        font-size: 14px;
    }

    .spp-register-card {
        padding: 30px;
    }

    .card-top {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 24px;
    }

    .brand-logo {
        width: 100px;
        height: auto;
    }

    .card-top h2 {
        margin: 0 0 4px;
        font-size: 26px;
    }

    .card-top p {
        margin: 0;
        color: #61766f;
    }

    .spp-register-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-block.full,
    .notice-box,
    .submit-btn,
    .back-link {
        grid-column: 1 / -1;
    }

    .form-block label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        width: 100%;
        border-radius: 14px;
        border: 1px solid #d5e2dc;
        padding: 12px 14px;
        min-height: 48px;
        background: #fff;
    }

    .field-help {
        margin-top: 8px;
        font-size: 13px;
        color: #5c726c;
    }

    .field-help.error {
        color: #b42318;
    }

    .field-help.success {
        color: #166534;
    }

    .notice-box {
        padding: 16px 18px;
        border-radius: 16px;
        background: #f6fbf8;
        border: 1px dashed #bfd8ce;
    }

    .notice-box strong,
    .notice-box span {
        display: block;
    }

    .notice-box span {
        color: #5c726c;
        margin-top: 6px;
    }

    .submit-btn {
        border: 0;
        border-radius: 16px;
        padding: 14px 18px;
        background: linear-gradient(135deg, #14532d, #0f766e);
        color: #fff;
        font-weight: 700;
    }

    .back-link {
        text-align: center;
        color: #14532d;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .spp-register-grid {
            grid-template-columns: 1fr;
        }

        .spp-register-hero h1 {
            font-size: 34px;
        }
    }

    @media (max-width: 640px) {
        .spp-register-shell {
            padding: 18px 12px;
        }

        .spp-register-card,
        .spp-register-hero {
            border-radius: 22px;
            padding: 22px;
        }

        .spp-register-form {
            grid-template-columns: 1fr;
        }

        .spp-register-hero h1 {
            font-size: 30px;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var scodInput = document.getElementById('scod');
        var madrasahNameInput = document.getElementById('madrasah_name');
        var scodHelp = document.getElementById('scodHelp');
        var lookupUrl = '{{ route('spp-operator.lookup-school') }}';
        var lookupTimer = null;

        if (!scodInput || !madrasahNameInput || !scodHelp) {
            return;
        }

        function setState(message, type, schoolName) {
            scodHelp.textContent = message;
            scodHelp.classList.remove('error', 'success');

            if (type) {
                scodHelp.classList.add(type);
            }

            madrasahNameInput.value = schoolName || '';
        }

        function lookupSchool() {
            var scod = scodInput.value.trim();

            if (!scod) {
                setState('Ketik SCOD sekolah untuk memunculkan nama sekolah otomatis.', '', '');
                return;
            }

            setState('Memeriksa SCOD sekolah...', '', '');

            fetch(lookupUrl + '?scod=' + encodeURIComponent(scod), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, status: response.status, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok && result.status !== 404 && result.status !== 422) {
                    throw new Error(result.data.message || 'Lookup SCOD gagal diproses.');
                }

                if (!result.data.found) {
                    setState(result.data.message || 'SCOD tidak ditemukan.', 'error', '');
                    return;
                }

                var schoolName = result.data.madrasah ? result.data.madrasah.name : '';
                var kabupaten = result.data.madrasah && result.data.madrasah.kabupaten ? result.data.madrasah.kabupaten + ' • ' : '';

                if (result.data.available === false) {
                    setState((result.data.message || 'Sekolah tidak tersedia.') + ' (' + schoolName + ')', 'error', schoolName);
                    return;
                }

                setState(kabupaten + 'Sekolah ditemukan dan siap didaftarkan.', 'success', schoolName);
            })
            .catch(function () {
                setState('Terjadi kesalahan saat memeriksa SCOD.', 'error', '');
            });
        }

        scodInput.addEventListener('input', function () {
            clearTimeout(lookupTimer);
            lookupTimer = setTimeout(lookupSchool, 350);
        });

        if (scodInput.value.trim() !== '') {
            lookupSchool();
        }
    });
</script>
@endsection
