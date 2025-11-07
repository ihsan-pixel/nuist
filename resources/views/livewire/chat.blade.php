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
                                        @if(count($availableUsers) > 0)
                                            @foreach($availableUsers as $user)
                                                <div class="p-3 border-bottom chat-user-item {{ $selectedUser == $user['id'] ? 'bg-primary bg-opacity-10' : '' }}"
                                                     style="cursor: pointer;"
                                                     wire:click="selectUser({{ $user['id'] }})">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-3">
                                                            @if($user['avatar'])
                                                                <img src="{{ asset('storage/app/public/' . $user['avatar']) }}"
                                                                     class="img-fluid rounded-circle"
                                                                     alt="{{ $user['name'] }}">
                                                            @else
                                                                <div class="avatar-xs">
                                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{ $user['name'] }}</h6>
                                                            <p class="text-muted mb-0 small">{{ $user['email'] }}</p>
                                                        </div>
                                                        @if(isset($unreadCounts[$user['id']]) && $unreadCounts[$user['id']] > 0)
                                                            <div class="badge bg-danger rounded-pill">
                                                                {{ $unreadCounts[$user['id']] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="p-4 text-center text-muted">
                                                <i class="bx bx-user-x display-4"></i>
                                                <p class="mt-2">Tidak ada pengguna tersedia untuk chat</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Area -->
                        <div class="col-lg-8">
                            @if($selectedUser)
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $selectedUserData = collect($availableUsers)->firstWhere('id', $selectedUser);
                                            @endphp
                                            @if($selectedUserData)
                                                <div class="avatar-xs me-3">
                                                    @if($selectedUserData['avatar'])
                                                        <img src="{{ asset('storage/app/public/' . $selectedUserData['avatar']) }}"
                                                             class="img-fluid rounded-circle"
                                                             alt="{{ $selectedUserData['name'] }}">
                                                    @else
                                                        <div class="avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-success text-white">
                                                                {{ strtoupper(substr($selectedUserData['name'], 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $selectedUserData['name'] }}</h6>
                                                    <p class="text-muted mb-0 small">{{ $selectedUserData['email'] }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Chat Messages -->
                                        <div class="chat-conversation p-3" style="height: 400px; overflow-y: auto; background: #f8f9fa; border-radius: 10px;">
                                            @if(count($chats) > 0)
                                                @foreach($chats as $chat)
                                                    <div class="chat-message {{ $chat['sender_id'] == auth()->id() ? 'text-end' : '' }} mb-3">
                                                        <div class="d-inline-block {{ $chat['sender_id'] == auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded-3"
                                                             style="max-width: 70%; word-wrap: break-word;">
                                                            <p class="mb-1">{{ $chat['message'] }}</p>
                                                            <small class="{{ $chat['sender_id'] == auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                                                {{ \Carbon\Carbon::parse($chat['created_at'])->format('H:i') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted py-5">
                                                    <i class="bx bx-message-square-dots display-4"></i>
                                                    <p class="mt-2">Belum ada pesan. Mulai percakapan!</p>
                                                </div>
                                            @endif
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
                                                            @error('message')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
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
                            @else
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="bx bx-chat display-4 text-muted"></i>
                                        <h5 class="mt-3">Pilih Pengguna</h5>
                                        <p class="text-muted">Pilih pengguna dari daftar di sebelah kiri untuk memulai percakapan</p>
                                    </div>
                                </div>
                            @endif
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
            @this.call('$refresh');
        }, 5000);
    });
    </script>
</div>
