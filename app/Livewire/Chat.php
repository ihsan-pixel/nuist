<?php

namespace App\Livewire;

use App\Mail\ChatNotification;
use App\Models\Chat as ChatModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Chat extends Component
{
    public $message = '';
    public $chats = [];
    public $selectedUser = null;
    public $availableUsers = [];
    public $unreadCounts = [];

    protected $listeners = ['refreshChat' => '$refresh'];

    public function mount()
    {
        $this->authorizeAccess();

        $this->loadAvailableUsers();
        $this->loadUnreadCounts();

        // Auto-select first available user if any
        if (count($this->availableUsers) > 0) {
            $this->selectedUser = $this->availableUsers[0]['id'];
            $this->loadChats();
        }
    }

    private function authorizeAccess()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access to chat feature.');
        }
    }

    private function loadAvailableUsers()
    {
        $currentUser = Auth::user();
        $oppositeRole = $currentUser->role === 'admin' ? 'super_admin' : 'admin';

        $this->availableUsers = User::where('role', $oppositeRole)
            ->where('id', '!=', $currentUser->id)
            ->select('id', 'name', 'email', 'avatar')
            ->get()
            ->toArray();
    }

    private function loadUnreadCounts()
    {
        $currentUser = Auth::user();

        foreach ($this->availableUsers as $user) {
            $this->unreadCounts[$user['id']] = ChatModel::unreadForUser($currentUser->id)
                ->where('sender_id', $user['id'])
                ->count();
        }
    }

    public function selectUser($userId)
    {
        $this->selectedUser = $userId;
        $this->loadChats();

        // Mark messages as read when selecting user
        ChatModel::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->loadUnreadCounts();
    }

    private function loadChats()
    {
        if (!$this->selectedUser) return;

        $this->chats = ChatModel::betweenUsers(Auth::id(), $this->selectedUser)
            ->with(['sender', 'receiver'])
            ->orderBy('sent_at', 'asc')
            ->get()
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        if (!$this->selectedUser) {
            session()->flash('error', 'Please select a user to chat with.');
            return;
        }

        $chat = ChatModel::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUser,
            'message' => trim($this->message),
            'sent_at' => now(),
        ]);

        $this->message = '';
        $this->loadChats();

        // Send email notification
        $receiver = User::find($this->selectedUser);
        if ($receiver && $receiver->email) {
            try {
                Mail::to($receiver->email)->send(new ChatNotification($chat));
            } catch (\Exception $e) {
                // Log error but don't interrupt chat flow
                \Log::error('Failed to send chat notification: ' . $e->getMessage());
            }
        }

        // Emit event for real-time updates
        $this->dispatch('messageSent', $chat->id);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
