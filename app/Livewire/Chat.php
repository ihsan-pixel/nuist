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

        $this->users = User::where('role', $oppositeRole)
            ->where('id', '!=', $currentUser->id)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();
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
