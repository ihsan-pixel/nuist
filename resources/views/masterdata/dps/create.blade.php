@extends('layouts.master')

@section('title')
    Tambah DPS
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Tambah DPS @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Validasi gagal:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dps.store') }}">
            @csrf

            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label">SCOD</label>
                    <select name="madrasah_id" id="madrasah_id" class="form-select" required>
                        <option value="">Pilih sekolah...</option>
                        @foreach($madrasahs as $m)
                            <option
                                value="{{ $m->id }}"
                                data-name="{{ $m->name }}"
                                data-scod="{{ $m->scod }}"
                                @selected((string)old('madrasah_id', $prefillMadrasahId) === (string)$m->id)
                            >
                                {{ $m->scod }} - {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-8">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" id="madrasah_name" class="form-control" value="" readonly>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Anggota DPS</div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                    <i class="bx bx-plus"></i> Tambah Baris
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="membersTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 34%;">Nama DPS</th>
                            <th style="width: 34%;">Unsur DPS</th>
                            <th style="width: 22%;">Periode</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="members[0][nama]" class="form-control" placeholder="Nama DPS" required>
                            </td>
                            <td>
                                <input type="text" name="members[0][unsur]" class="form-control" placeholder="Contoh: Akademisi / Tokoh Masyarakat" required>
                            </td>
                            <td>
                                <input type="text" name="members[0][periode]" class="form-control" value="2024-2026" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger removeRowBtn" disabled title="Minimal 1 baris">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('dps.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const madrasahSelect = document.getElementById('madrasah_id');
    const madrasahName = document.getElementById('madrasah_name');

    function syncMadrasahName() {
        const opt = madrasahSelect.options[madrasahSelect.selectedIndex];
        madrasahName.value = opt && opt.dataset ? (opt.dataset.name || '') : '';
    }
    madrasahSelect.addEventListener('change', syncMadrasahName);
    syncMadrasahName();

    const tableBody = document.querySelector('#membersTable tbody');
    const addRowBtn = document.getElementById('addRowBtn');

    function updateRemoveButtons() {
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.removeRowBtn');
            if (!btn) return;
            btn.disabled = rows.length === 1;
            btn.title = (rows.length === 1) ? 'Minimal 1 baris' : 'Hapus baris';
        });
    }

    function renumberNames() {
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            row.querySelectorAll('input[name^="members["]').forEach((input) => {
                input.name = input.name.replace(/members\\[\\d+\\]/, `members[${idx}]`);
            });
        });
    }

    addRowBtn.addEventListener('click', () => {
        const idx = tableBody.querySelectorAll('tr').length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="members[${idx}][nama]" class="form-control" placeholder="Nama DPS" required></td>
            <td><input type="text" name="members[${idx}][unsur]" class="form-control" placeholder="Contoh: Akademisi / Tokoh Masyarakat" required></td>
            <td><input type="text" name="members[${idx}][periode]" class="form-control" value="2024-2026" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger removeRowBtn" title="Hapus baris">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(tr);
        updateRemoveButtons();
    });

    tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('.removeRowBtn');
        if (!btn) return;
        const row = btn.closest('tr');
        if (!row) return;
        row.remove();
        renumberNames();
        updateRemoveButtons();
    });

    updateRemoveButtons();
})();
</script>
@endpush
@endsection

