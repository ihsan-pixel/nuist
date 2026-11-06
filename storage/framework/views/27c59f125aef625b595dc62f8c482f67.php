<div wire:poll.15s="refreshData">
    <div class="active-users-heading d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h4 class="mb-1"><i class="bx bx-user-check text-success me-2"></i>Pengguna Aktif</h4>
            <p class="text-muted mb-0">Pantau pengguna yang sedang aktif dalam sistem secara realtime.</p>
        </div>
        <div class="active-users-search">
            <i class="bx bx-search"></i>
            <input type="search" id="activeUserSearch" placeholder="Cari nama, email, atau sekolah..." autocomplete="off">
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm shrink-0">
                            <span class="avatar-title bg-primary rounded-circle fs-3">
                                <i class="bx bx-user-check"></i>
                            </span>
                        </div>
                        <div class="grow ms-3">
                            <p class="text-muted mb-2">Total Aktif</p>
                            <h5 class="mb-0"><?php echo e($totalActive); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $roleLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm shrink-0">
                            <span class="avatar-title bg-success rounded-circle fs-3">
                                <i class="bx bx-group"></i>
                            </span>
                        </div>
                        <div class="grow ms-3">
                            <p class="text-muted mb-2"><?php echo e($label); ?></p>
                            <h5 class="mb-0"><?php echo e(isset($activeUsersByRole[$role]) ? $activeUsersByRole[$role]->count() : 0); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    
    <div class="row">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activeUsersByRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="col-12 mb-4 active-role-section" data-role-section="<?php echo e($role); ?>">
            <div class="card user-card">
                <div class="role-header">
                    <h5 class="mb-0">
                        <i class="bx bx-group me-2"></i>
                        <?php echo e($roleLabels[$role] ?? ucfirst($role)); ?>

                        <span class="badge bg-light text-dark ms-2"><?php echo e($users->count()); ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="user-list">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="user-item" data-user-search="<?php echo e(strtolower($user['name'].' '.$user['email'].' '.($user['madrasah'] ?? '').' '.$user['nuist_id'])); ?>">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e($user['avatar']); ?>"
                                     alt="Avatar" class="user-avatar me-3">
                                <div class="grow">
                                    <h6 class="mb-1"><?php echo e($user['name']); ?></h6>
                                    <p class="text-muted mb-0 small">
                                        <i class="bx bx-envelope me-1"></i><?php echo e($user['email']); ?>

                                    </p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user['madrasah']): ?>
                                        <p class="text-muted mb-0 small">
                                            <i class="bx bx-building me-1"></i><?php echo e($user['madrasah']); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <p class="text-muted mb-0 small">
                                        <i class="bx bx-time me-1"></i>Terakhir aktif: <?php echo e($user['last_seen']); ?>

                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted"><?php echo e($user['nuist_id']); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Tidak ada pengguna aktif</p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <style>
    .user-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 .5rem 1.5rem rgba(15, 23, 42, .07);
        transition: transform 0.2s;
    }

    .user-card:hover {
        transform: translateY(-2px);
    }

    .role-header {
        background: linear-gradient(135deg, #075e4d 0%, #0b8b68 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: .9rem 1.15rem;
    }

    .user-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
        gap: .85rem;
        max-height: 560px;
        padding: 1rem;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .user-item {
        min-width: 0;
        padding: .9rem;
        border: 1px solid #e7edef;
        border-radius: .8rem;
        background: #fff;
        transition: background-color 0.2s;
        overflow: hidden;
    }

    .active-users-heading h4 { font-weight: 700; color: #192d37; }
    .active-users-search { position: relative; width: min(100%, 360px); }
    .active-users-search i { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); color: #7b8790; }
    .active-users-search input { width: 100%; border: 1px solid #dfe8eb; border-radius: 999px; padding: .65rem 1rem .65rem 2.35rem; outline: none; }
    .active-users-search input:focus { border-color: #0b8b68; box-shadow: 0 0 0 .2rem rgba(11, 139, 104, .12); }

    .user-item:hover {
        background-color: #f7fbfa;
        border-color: #9ed7c5;
        box-shadow: 0 .35rem .8rem rgba(8, 116, 90, .08);
    }

    .user-item > .d-flex {
        min-width: 0;
        align-items: flex-start !important;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        flex: 0 0 40px;
    }

    .stats-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 .5rem 1.5rem rgba(15, 23, 42, .07);
    }

    @media (max-width: 575.98px) {
        .user-list { grid-template-columns: 1fr; padding: .75rem; }
        .active-users-search { width: 100%; }
    }

    .user-item .grow {
        min-width: 0;
        overflow: hidden;
    }

    .user-item .text-end {
        flex: 0 0 auto;
        margin-left: .5rem;
    }

    .user-item .text-end small {
        display: inline-block;
        padding: .2rem .4rem;
        border-radius: .35rem;
        background: #f0f5f6;
        color: #62727a !important;
        font-size: .68rem;
    }

    .user-item h6,
    .user-item p {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    </style>
    <script>
    document.addEventListener('input', function (event) {
        if (event.target.id !== 'activeUserSearch') return;
        const query = event.target.value.toLowerCase().trim();
        document.querySelectorAll('.active-role-section').forEach(section => {
            let visible = 0;
            section.querySelectorAll('.user-item[data-user-search]').forEach(item => {
                const match = !query || item.dataset.userSearch.includes(query);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            section.style.display = visible || !query ? '' : 'none';
        });
    });
    </script>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/livewire/active-users.blade.php ENDPATH**/ ?>