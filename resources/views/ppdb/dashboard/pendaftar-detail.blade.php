<div class="pendaftar-detail">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-card-details me-2"></i>
                        Detail Pendaftar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Data Pribadi -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="mdi mdi-account me-1"></i>Data Pribadi</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Nama Lengkap</strong></td>
                                    <td>: {{ $pendaftar->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIK</strong></td>
                                    <td>: {{ $pendaftar->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NISN</strong></td>
                                    <td>: {{ $pendaftar->nisn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tempat Lahir</strong></td>
                                    <td>: {{ $pendaftar->tempat_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir</strong></td>
                                    <td>: {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin</strong></td>
                                    <td>: {{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Agama</strong></td>
                                    <td>: {{ $pendaftar->agama ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Data Sekolah -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="mdi mdi-school me-1"></i>Data Sekolah</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Asal Sekolah</strong></td>
                                    <td>: {{ $pendaftar->asal_sekolah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NPSN Sekolah</strong></td>
                                    <td>: {{ $pendaftar->npsn_sekolah_asal ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tahun Lulus</strong></td>
                                    <td>: {{ $pendaftar->tahun_lulus ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nilai Rata-rata</strong></td>
                                    <td>: {{ $pendaftar->rata_rata_nilai_raport ?? $pendaftar->nilai ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jurusan Pilihan</strong></td>
                                    <td>: {{ $pendaftar->jurusan_pilihan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jalur PPDB</strong></td>
                                    <td>: {{ $pendaftar->jalur ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Data Orang Tua -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="mdi mdi-account-group me-1"></i>Data Orang Tua</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Nama Ayah</strong></td>
                                    <td>: {{ $pendaftar->nama_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pekerjaan Ayah</strong></td>
                                    <td>: {{ $pendaftar->pekerjaan_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP Ayah</strong></td>
                                    <td>: {{ $pendaftar->nomor_hp_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Ibu</strong></td>
                                    <td>: {{ $pendaftar->nama_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pekerjaan Ibu</strong></td>
                                    <td>: {{ $pendaftar->pekerjaan_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP Ibu</strong></td>
                                    <td>: {{ $pendaftar->nomor_hp_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Keluarga</strong></td>
                                    <td>: {{ $pendaftar->status_keluarga ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Data Kontak & Alamat -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="mdi mdi-phone me-1"></i>Kontak & Alamat</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>No. WhatsApp Siswa</strong></td>
                                    <td>: {{ $pendaftar->ppdb_nomor_whatsapp_siswa ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Siswa</strong></td>
                                    <td>: {{ $pendaftar->ppdb_email_siswa ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat Lengkap</strong></td>
                                    <td>: {{ $pendaftar->alamat_lengkap ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Provinsi</strong></td>
                                    <td>: {{ $pendaftar->provinsi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kabupaten</strong></td>
                                    <td>: {{ $pendaftar->kabupaten ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kecamatan</strong></td>
                                    <td>: {{ $pendaftar->kecamatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Desa</strong></td>
                                    <td>: {{ $pendaftar->desa ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Data PPDB -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="mdi mdi-clipboard-check me-1"></i>Data PPDB</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Nomor Pendaftaran</strong></td>
                                    <td>: {{ $pendaftar->nomor_pendaftaran ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:
                                        <span class="badge bg-{{ $pendaftar->status == 'lulus' ? 'success' : ($pendaftar->status == 'tidak_lulus' ? 'danger' : ($pendaftar->status == 'verifikasi' ? 'info' : 'warning')) }}">
                                            @if($pendaftar->status == 'lulus')
                                                Lulus
                                            @elseif($pendaftar->status == 'tidak_lulus')
                                                Tidak Lulus
                                            @elseif($pendaftar->status == 'verifikasi')
                                                Dalam Verifikasi
                                            @else
                                                Menunggu Verifikasi
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ranking</strong></td>
                                    <td>: {{ $pendaftar->ranking ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Skor Total</strong></td>
                                    <td>: {{ $pendaftar->skor_total ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Rencana Lulus</strong></td>
                                    <td>: {{ $pendaftar->rencana_lulus == 'kuliah' ? 'Kuliah' : ($pendaftar->rencana_lulus == 'kerja' ? 'Bekerja' : '-') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Daftar</strong></td>
                                    <td>: {{ $pendaftar->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Skor Detail -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="mdi mdi-chart-line me-1"></i>Detail Skor</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Skor Nilai</strong></td>
                                    <td>: {{ $pendaftar->skor_nilai ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Skor Prestasi</strong></td>
                                    <td>: {{ $pendaftar->skor_prestasi ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Skor Domisili</strong></td>
                                    <td>: {{ $pendaftar->skor_domisili ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Skor Dokumen</strong></td>
                                    <td>: {{ $pendaftar->skor_dokumen ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong><strong>Skor Total</strong></strong></td>
                                    <td>: <strong>{{ $pendaftar->skor_total ?? 0 }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Berkas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-file-document-multiple me-2"></i>
                        Berkas & Dokumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if($pendaftar->berkas_kk)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_kk) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-file-document-outline me-2 text-primary"></i>
                                Kartu Keluarga
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_ijazah)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_ijazah) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-certificate me-2 text-success"></i>
                                Ijazah
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_akta_kelahiran)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_akta_kelahiran) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-baby-face-outline me-2 text-info"></i>
                                Akta Kelahiran
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_ktp_ayah)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_ktp_ayah) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-card-account-details-outline me-2 text-warning"></i>
                                KTP Ayah
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_ktp_ibu)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_ktp_ibu) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-card-account-details-outline me-2 text-warning"></i>
                                KTP Ibu
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_raport)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_raport) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-book-open-page-variant me-2 text-secondary"></i>
                                Raport
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_sertifikat_prestasi)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_sertifikat_prestasi) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-trophy-outline me-2 text-warning"></i>
                                Sertifikat Prestasi
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_kip_pkh)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_kip_pkh) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-hand-heart me-2 text-danger"></i>
                                KIP/PKH
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_bukti_domisili)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_bukti_domisili) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-map-marker-outline me-2 text-info"></i>
                                Bukti Domisili
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_surat_mutasi)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_surat_mutasi) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-file-move-outline me-2 text-secondary"></i>
                                Surat Mutasi
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_surat_keterangan_lulus)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_surat_keterangan_lulus) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-school-outline me-2 text-success"></i>
                                Surat Keterangan Lulus
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if($pendaftar->berkas_skl)
                            <a href="{{ asset('storage/' . $pendaftar->berkas_skl) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="mdi mdi-certificate-outline me-2 text-success"></i>
                                SKL
                                <i class="mdi mdi-open-in-new ms-auto text-muted"></i>
                            </a>
                        @endif

                        @if(empty(array_filter([
                            $pendaftar->berkas_kk, $pendaftar->berkas_ijazah, $pendaftar->berkas_akta_kelahiran,
                            $pendaftar->berkas_ktp_ayah, $pendaftar->berkas_ktp_ibu, $pendaftar->berkas_raport,
                            $pendaftar->berkas_sertifikat_prestasi, $pendaftar->berkas_kip_pkh, $pendaftar->berkas_bukti_domisili,
                            $pendaftar->berkas_surat_mutasi, $pendaftar->berkas_surat_keterangan_lulus, $pendaftar->berkas_skl
                        ])))
                            <div class="text-center text-muted py-3">
                                <i class="mdi mdi-file-document-multiple-outline fs-1"></i>
                                <p class="mb-0">Belum ada berkas yang diupload</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Verifikasi Info -->
            @if($pendaftar->diverifikasi_oleh || $pendaftar->diseleksi_oleh)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-check-circle me-2"></i>
                        Info Verifikasi
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendaftar->diverifikasi_oleh)
                        <div class="mb-2">
                            <small class="text-muted">Diverifikasi oleh:</small><br>
                            <strong>{{ $pendaftar->verifikator->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">{{ $pendaftar->diverifikasi_tanggal ? $pendaftar->diverifikasi_tanggal->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                        @if($pendaftar->catatan_verifikasi)
                            <div class="mb-2">
                                <small class="text-muted">Catatan Verifikasi:</small><br>
                                <p class="mb-0 small">{{ $pendaftar->catatan_verifikasi }}</p>
                            </div>
                        @endif
                    @endif

                    @if($pendaftar->diseleksi_oleh)
                        <div>
                            <small class="text-muted">Diseleksi oleh:</small><br>
                            <strong>{{ $pendaftar->penyeleksi->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">{{ $pendaftar->diseleksi_tanggal ? $pendaftar->diseleksi_tanggal->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
