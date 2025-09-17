<?php $__env->startSection('title'); ?> Dasbor <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboards <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-success-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-success p-3">
                            <h5 class="text-success">Selamat Datang!</h5>
                            <p>Aplikasi NUIST</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
<img src="<?php echo e(isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg')); ?>" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15"><?php echo e(Str::ucfirst(Auth::user()->name)); ?></h5>
                        <p class="text-muted mb-0 text-truncate">Nuist ID : <?php echo e(Auth::user()->nuist_id ?? '-'); ?></p>
                    </div>
                    <div class="col-sm-8">
                        
                            
                        
                    </div>
                </div>
            </div>
        </div>

        <?php if(Auth::user()->role === 'tenaga_pendidik'): ?>
        
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Keaktifan</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-muted">Bulan ini</p>
                        <h3><?php echo e(round($attendanceData['kehadiran'] ?? 0)); ?>%</h3>
                        <p class="text-muted">
                            <span class="text-success me-2"> <?php echo e(round($attendanceData['kehadiran'] ?? 0)); ?>% <i class="mdi mdi-arrow-up"></i> </span> Kehadiran
                        </p>
                        <div class="row mt-3">
                            
                            <div class="col-6">
                                <small class="text-muted">Total Presensi</small>
                                <h6><?php echo e($attendanceData['total_presensi'] ?? 0); ?></h6>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('presensi.index')); ?>" class="btn btn-success waves-effect waves-light btn-sm">Lihat Detail <i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mt-4 mt-sm-0">
                            <div id="donut-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts"></div>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-0">Persentase kehadiran berdasarkan hari kerja (Senin-Sabtu, exclude hari libur).</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if(!in_array(Auth::user()->role, ['admin', 'super_admin'])): ?>
    <div class="col-xl-8">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi User</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">Asal Madrasah/Sekolah :</small>
                        <h6><?php echo e(Auth::user()->madrasah ? Auth::user()->madrasah->name : '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tempat Lahir</small>
                        <h6><?php echo e(Auth::user()->tempat_lahir ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal Lahir</small>
                        <h6><?php echo e(Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">TMT</small>
                        <h6><?php echo e(Auth::user()->tmt ? \Carbon\Carbon::parse(Auth::user()->tmt)->format('d F Y') : '-'); ?></h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">NUPTK</small>
                        <h6><?php echo e(Auth::user()->nuptk ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">NPK</small>
                        <h6><?php echo e(Auth::user()->npk ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Kartanu</small>
                        <h6><?php echo e(Auth::user()->kartanu ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">NIP Ma'arif</small>
                        <h6><?php echo e(Auth::user()->nip_maarif ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-success">Status Kepegawaian</small>
                        <h6><?php echo e(Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-success">Ketugasan</small>
                        <h6><?php echo e(Auth::user()->ketugasan ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-info">Pendidikan Terakhir, Tahun Lulus</small>
                        <h6><?php echo e(Auth::user()->pendidikan_terakhir ?? '-'); ?></h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-warning">Program Studi</small>
                        <h6><?php echo e(Auth::user()->program_studi ?? '-'); ?></h6>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($showUsers): ?>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Rekan Guru/Pegawai Se-Madrasah/Sekolah</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Ketugasan</th>
                                <th>Status Kepegawaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($users->firstItem() + $index); ?></td>
                                <td>
<img src="<?php echo e(isset($user->avatar) ? asset('storage/' . $user->avatar) : asset('build/images/users/avatar-1.jpg')); ?>" alt="Foto <?php echo e($user->name); ?>" class="rounded-circle" width="40" height="40">
                                </td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->ketugasan ?? '-'); ?></td>
                                <td><?php echo e($user->statusKepegawaian ? $user->statusKepegawaian->name : '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <?php echo e($users->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>










<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var attendanceData = <?php echo json_encode($attendanceData ?? ['kehadiran' => 0, 'izin_sakit' => 0, 'alpha' => 0]) ?>;

        var options = {
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                        },
                        value: {
                            fontSize: '14px',
                            formatter: function (val) {
                                return val + "%";
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function () {
                                return 100 + "%";
                            }
                        }
                    }
                }
            },
            colors: ['#198754', '#ffc107', '#dc3545'],
            series: [
                attendanceData.kehadiran,
                attendanceData.izin_sakit,
                attendanceData.alpha
            ],
            labels: ['Kehadiran', 'Izin/Sakit', 'Tidak Hadir'],
            legend: {
                position: 'bottom',
                formatter: function (val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
                }
            }
        };

        var chartElement = document.querySelector("#donut-chart");
        if (chartElement) {
            var chart = new ApexCharts(
                chartElement,
                options
            );

            chart.render();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apk_nuist\resources\views/dashboard/index.blade.php ENDPATH**/ ?>