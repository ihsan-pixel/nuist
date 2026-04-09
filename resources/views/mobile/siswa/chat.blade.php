@extends('layouts.mobile')

@section('title', 'Chat Admin Sekolah')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Chat Admin Sekolah', 'subtitle' => $adminContact->name ?? 'Belum ada admin'])

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="chat-card">
        <div class="section-title">
            <h5>💬 Chat admin sekolah</h5>
            @if($adminContact)
                <span class="pill pill-info">{{ $adminContact->role }}</span>
            @endif
        </div>

        <div class="chat-window">
            @forelse($chats as $chat)
                <div class="bubble {{ $chat->sender_id === $studentUser->id ? 'me' : 'them' }}">
                    {{ $chat->message }}
                    <div style="font-size:10px; margin-top:6px; opacity:.85;">
                        {{ optional($chat->created_at)->translatedFormat('d M H:i') }}
                    </div>
                </div>
            @empty
                <div class="bubble them">
                    Halo, silakan kirim pertanyaan terkait tagihan, verifikasi transfer, atau bukti pembayaran.
                </div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('mobile.siswa.chat.send') }}">
            @csrf
            <textarea name="message" class="form-control" rows="3" placeholder="Tulis pesan ke admin sekolah..." required>{{ old('message') }}</textarea>
            @error('message')
                <small class="text-danger d-block mt-2">{{ $message }}</small>
            @enderror
            <button type="submit" class="cta-btn mt-3">
                <i class="bx bx-send"></i>Kirim pesan
            </button>
        </form>
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
