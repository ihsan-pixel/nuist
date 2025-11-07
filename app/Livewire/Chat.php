<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat as ChatModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChatNotification;

class Chat extends Component
{
    public $message = '';
    public $selectedUserId = null;
    public $messages = [];
    public $users = [];
    public $search = '';

    protected $listeners = ['refreshChat' => '$refresh'];

    public function mount()
    {
        $this->authorizeAccess();
        $this->loadUsers();
        $this->loadMessages();
    }

    private function authorizeAccess()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access to chat');
        }
    }

    public function loadUsers()
    {
        $currentUser = Auth::user();
        $allowedRoles = ['admin', 'super_admin'];

        // Get users with opposite roles only
        $oppositeRole = $currentUser->role === 'admin' ? 'super_admin' : 'admin';

        // For super_admin, order by latest message timestamp (most recent first)
        if ($currentUser->role === 'super_admin') {
            $this->users = User::select('users.*', \DB::raw('MAX(chats.created_at) as latest_message_at'))
                ->leftJoin('chats', function ($join) use ($currentUser) {
                    $join->where(function ($query) use ($currentUser) {
                        $query->where('chats.sender_id', '=', 'users.id')
                              ->where('chats.receiver_id', '=', $currentUser->id);
                    })
                    ->orWhere(function ($query) use ($currentUser) {
                        $query->where('chats.receiver_id', '=', 'users.id')
                              ->where('chats.sender_id', '=', $currentUser->id);
                    });
                })
                ->where('users.role', $oppositeRole)
                ->where('users.id', '!=', $currentUser->id)
                ->when($this->search, function ($query) {
                    $query->where('users.name', 'like', '%' . $this->search . '%')
                          ->orWhere('users.email', 'like', '%' . $this->search . '%');
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.remember_token', 'users.created_at', 'users.updated_at', 'users.role', 'users.nuist_id', 'users.tempat_lahir', 'users.tanggal_lahir', 'users.no_hp', 'users.kartanu', 'users.nip', 'users.nuptk', 'users.npk', 'users.madrasah_id', 'users.pendidikan_terakhir', 'users.tahun_lulus', 'users.program_studi', 'users.status_kepegawaian_id', 'users.tmt', 'users.ketugasan', 'users.mengajar', 'users.avatar', 'users.alamat', 'users.pemenuhan_beban_kerja_lain', 'users.madrasah_id_tambahan', 'users.password_changed', 'users.last_seen', 'users.jabatan')
                ->orderByRaw('MAX(chats.created_at) IS NULL ASC, MAX(chats.created_at) DESC')
                ->orderBy('users.name')
                ->get();
        } else {
            $this->users = User::where('role', $oppositeRole)
                ->where('id', '!=', $currentUser->id)
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy('name')
                ->get();
        }


    }

    public function loadMessages()
    {
        if ($this->selectedUserId) {
            $this->messages = ChatModel::betweenUsers(Auth::id(), $this->selectedUserId)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        } else {
            $this->messages = [];
        }
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->loadMessages();

        // Mark messages as read
        ChatModel::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
            'selectedUserId' => 'required|exists:users,id',
        ]);

        $currentUser = Auth::user();
        $receiver = User::find($this->selectedUserId);

        // Validate that users have opposite roles
        if (!in_array($currentUser->role, ['admin', 'super_admin']) ||
            !in_array($receiver->role, ['admin', 'super_admin']) ||
            $currentUser->role === $receiver->role) {
            $this->addError('message', 'Invalid chat participants');
            return;
        }

        $chat = ChatModel::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $this->selectedUserId,
            'message' => trim($this->message),
            'sent_at' => now(),
        ]);

        $this->message = '';
        $this->loadMessages();
        $this->loadUsers(); // Reload users to update order for super_admin

        // Send email notification to super_admin if receiver is super_admin
        if ($receiver->role === 'super_admin') {
            try {
                Mail::to($receiver->email)->send(new ChatNotification($chat, $currentUser));
            } catch (\Exception $e) {
                // Log error but don't stop the chat
                \Log::error('Failed to send chat notification email: ' . $e->getMessage());
            }
        }

        $this->dispatch('messageSent');
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
