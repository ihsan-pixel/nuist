<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-chat me-2"></i>Chat Admin & Super Admin
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- User List Sidebar -->
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-user-circle me-2"></i>Pengguna Tersedia
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="chat-user-list" style="max-height: 500px; overflow-y: auto;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($availableUsers) > 0): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $availableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="p-3 border-bottom chat-user-item <?php echo e($selectedUser == $user['id'] ? 'bg-primary bg-opacity-10' : ''); ?>"
                                                     style="cursor: pointer;"
                                                     wire:click="selectUser(<?php echo e($user['id']); ?>)">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-3">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user['avatar']): ?>
                                                                <img src="<?php echo e(asset('storage/app/public/' . $user['avatar'])); ?>"
                                                                     class="img-fluid rounded-circle"
                                                                     alt="<?php echo e($user['name']); ?>">
                                                            <?php else: ?>
                                                                <div class="avatar-xs">
                                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                                        <?php echo e(strtoupper(substr($user['name'], 0, 1))); ?>

                                                                    </span>
                                                                </div>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0"><?php echo e($user['name']); ?></h6>
                                                            <p class="text-muted mb-0 small"><?php echo e($user['email']); ?></p>
                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($unreadCounts[$user['id']]) && $unreadCounts[$user['id']] > 0): ?>
                                                            <div class="badge bg-danger rounded-pill">
                                                                <?php echo e($unreadCounts[$user['id']]); ?>

                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <div class="p-4 text-center text-muted">
                                                <i class="bx bx-user-x display-4"></i>
                                                <p class="mt-2">Tidak ada pengguna tersedia untuk chat</p>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Area -->
                        <div class="col-lg-8">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser): ?>
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <div class="d-flex align-items-center">
                                            <?php
                                                $selectedUserData = collect($availableUsers)->firstWhere('id', $selectedUser);
                                            ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUserData): ?>
                                                <div class="avatar-xs me-3">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUserData['avatar']): ?>
                                                        <img src="<?php echo e(asset('storage/app/public/' . $selectedUserData['avatar'])); ?>"
                                                             class="img-fluid rounded-circle"
                                                             alt="<?php echo e($selectedUserData['name']); ?>">
                                                    <?php else: ?>
                                                        <div class="avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-success text-white">
                                                                <?php echo e(strtoupper(substr($selectedUserData['name'], 0, 1))); ?>

                                                            </span>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($selectedUserData['name']); ?></h6>
                                                    <p class="text-muted mb-0 small"><?php echo e($selectedUserData['email']); ?></p>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Chat Messages -->
                                        <div class="chat-conversation p-3" style="height: 400px; overflow-y: auto; background: #f8f9fa; border-radius: 10px;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($chats) > 0): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="chat-message <?php echo e($chat['sender_id'] == auth()->id() ? 'text-end' : ''); ?> mb-3">
                                                        <div class="d-inline-block <?php echo e($chat['sender_id'] == auth()->id() ? 'bg-primary text-white' : 'bg-light'); ?> p-3 rounded-3"
                                                             style="max-width: 70%; word-wrap: break-word;">
                                                            <p class="mb-1"><?php echo e($chat['message']); ?></p>
                                                            <small class="<?php echo e($chat['sender_id'] == auth()->id() ? 'text-white-50' : 'text-muted'); ?>">
                                                                <?php echo e(\Carbon\Carbon::parse($chat['created_at'])->format('H:i')); ?>

                                                            </small>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php else: ?>
                                                <div class="text-center text-muted py-5">
                                                    <i class="bx bx-message-square-dots display-4"></i>
                                                    <p class="mt-2">Belum ada pesan. Mulai percakapan!</p>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <!-- Message Input -->
                                        <div class="chat-input-section mt-3">
                                            <form wire:submit.prevent="sendMessage">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="position-relative">
                                                            <input type="text"
                                                                   wire:model="message"
                                                                   class="form-control form-control-lg bg-light border-0 pe-5"
                                                                   placeholder="Ketik pesan Anda..."
                                                                   style="border-radius: 25px; padding: 12px 20px;"
                                                                   wire:keydown.enter="sendMessage">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="submit"
                                                                class="btn btn-primary btn-lg rounded-pill px-4"
                                                                :disabled="!$wire.message.trim()"
                                                                wire:loading.attr="disabled">
                                                            <span wire:loading.remove>
                                                                <i class="bx bx-send"></i>
                                                            </span>
                                                            <span wire:loading>
                                                                <i class="bx bx-loader-alt bx-spin"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="bx bx-chat display-4 text-muted"></i>
                                        <h5 class="mt-3">Pilih Pengguna</h5>
                                        <p class="text-muted">Pilih pengguna dari daftar di sebelah kiri untuk memulai percakapan</p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .chat-user-item:hover {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }

    .chat-conversation::-webkit-scrollbar {
        width: 6px;
    }

    .chat-conversation::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .chat-conversation::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .chat-conversation::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>

    <script>
    document.addEventListener('livewire:loaded', () => {
        // Auto scroll to bottom of chat
        function scrollToBottom() {
            const chatContainer = document.querySelector('.chat-conversation');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }

        // Scroll to bottom on load
        scrollToBottom();

        // Listen for new messages
        Livewire.on('messageSent', () => {
            setTimeout(scrollToBottom, 100);
        });

        // Refresh chat every 5 seconds for real-time updates
        setInterval(() => {
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('$refresh');
        }, 5000);
    });
    </script>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/livewire/chat.blade.php ENDPATH**/ ?>