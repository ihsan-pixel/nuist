<div>
    <div class="d-lg-flex">
        <!-- Sidebar Chat List -->
        <div class="chat-leftsidebar me-lg-4">
            <div class="py-4 border-bottom">
                <div class="d-flex">
                    <div class="flex-shrink-0 align-self-center me-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" class="avatar-xs rounded-circle" alt="">
                        @else
                            <div class="avatar-xs">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-15 mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">
                            <i class="mdi mdi-circle text-success align-middle me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Search Box -->
            <div class="search-box chat-search-box py-4">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Cari pengguna..." wire:model.live="search">
                    <i class="bx bx-search-alt search-icon"></i>
                </div>
            </div>

            <!-- Chat List -->
            <div class="chat-leftsidebar-nav">
                <div class="tab-content py-4">
                    <div class="tab-pane show active" id="chat">
                        <div>
                            <h5 class="font-size-14 mb-3">Pengguna Tersedia</h5>
                            <ul class="list-unstyled chat-list" data-simplebar style="max-height: 410px;">
                                @forelse($users as $user)
                                    <li class="{{ $selectedUserId == $user->id ? 'active' : '' }}">
                                        <a href="#" wire:click.prevent="selectUser({{ $user->id }})">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 align-self-center me-3">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" class="rounded-circle avatar-xs" alt="">
                                                    @else
                                                        <div class="avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-1">{{ $user->name }}</h5>
                                                    <p class="text-truncate mb-0">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                                                </div>
                                                @php
                                                    $unreadCount = \App\Models\Chat::where('sender_id', $user->id)
                                                        ->where('receiver_id', Auth::id())
                                                        ->where('is_read', false)
                                                        ->count();
                                                @endphp
                                                @if($unreadCount > 0)
                                                    <div class="font-size-11 badge bg-danger">{{ $unreadCount }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center py-4">
                                        <p class="text-muted">Tidak ada pengguna tersedia untuk chat</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="w-100 user-chat">
            @if($selectedUserId)
                @php
                    $selectedUser = \App\Models\User::find($selectedUserId);
                @endphp
                <div class="card">
                    <!-- Chat Header -->
                    <div class="p-4 border-bottom">
                        <div class="row">
                            <div class="col-md-4 col-9">
                                <h5 class="font-size-15 mb-1">{{ $selectedUser->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="mdi mdi-circle text-success align-middle me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $selectedUser->role)) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="chat-conversation p-3">
                        <ul class="list-unstyled mb-0" data-simplebar style="max-height: 486px;" wire:poll.3s="loadMessages" id="chat-messages">
                            @if(count($messages) > 0)
                                @foreach($messages as $message)
                                    <li class="{{ $message['sender_id'] == Auth::id() ? 'right' : '' }}">
                                        <div class="conversation-list">
                                            <div class="ctext-wrap">
                                                <div class="conversation-name">{{ $message['sender']['name'] }}</div>
                                                <p>{{ $message['message'] }}</p>
                                                <p class="chat-time mb-0">
                                                    <i class="bx bx-time-five align-middle me-1"></i>
                                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                                                    @if($message['sender_id'] == Auth::id())
                                                        @if(isset($message['sent_at']) && $message['sent_at'])
                                                            <i class="bx bx-check align-middle ms-1 text-success"></i>
                                                        @else
                                                            <i class="bx bx-check align-middle ms-1"></i>
                                                        @endif
                                                        @if(isset($message['is_read']) && $message['is_read'])
                                                            <i class="bx bx-check align-middle text-success"></i>
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-center py-4">
                                    <p class="text-muted">Belum ada pesan. Mulai percakapan!</p>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Message Input -->
                    <div class="p-3 chat-input-section">
                        <form wire:submit.prevent="sendMessage" id="chat-form">
                            <div class="row">
                                <div class="col">
                                    <div class="position-relative">
                                        <input type="text"
                                               class="form-control chat-input @error('message') is-invalid @enderror"
                                               placeholder="Ketik pesan..."
                                               wire:model="message"
                                               wire:keydown.enter.prevent="sendMessage">
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit"
                                            class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="btn-loading">
                                        <span wire:loading.remove>Kirim</span>
                                        <span wire:loading>Mengirim...</span>
                                        <i class="mdi mdi-send"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="p-5 text-center">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="bx bx-message-alt-detail font-size-24"></i>
                            </div>
                        </div>
                        <h5 class="font-size-16 mb-3">Pilih pengguna untuk memulai chat</h5>
                        <p class="text-muted">Pilih pengguna dari daftar di sebelah kiri untuk memulai percakapan.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:loaded', () => {
    // Auto scroll to bottom when new messages arrive
    Livewire.on('messageSent', () => {
        setTimeout(() => {
            const chatContainer = document.querySelector('.chat-conversation .simplebar-content-wrapper');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }, 100);
    });

    // Scroll to bottom on initial load
    setTimeout(() => {
        const chatContainer = document.querySelector('.chat-conversation .simplebar-content-wrapper');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }, 100);

    // Prevent form submission on Enter key to avoid double submission
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.closest('#chat-form')) {
            e.preventDefault();
            // Let Livewire handle the submission
        }
    });

    // Remove React DevTools console message
    if (window.__REACT_DEVTOOLS_GLOBAL_HOOK__) {
        window.__REACT_DEVTOOLS_GLOBAL_HOOK__._renderers = {};
    }
});
</script>
@endpush
