{{-- resources/views/messages/inbox.blade.php --}}
@extends('layouts.app')
@section('title', 'Messages')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Messages</h1>
    @if($conversations->isEmpty())
        <div class="card text-center py-12 text-gray-400">
            <i class="fas fa-inbox text-5xl mb-3"></i>
            <p>Aucune conversation pour le moment.</p>
        </div>
    @else
        <div class="card divide-y">
            @foreach($conversations as $otherUserId => $message)
            @php
                $other = $message->sender_id === Auth::id() ? $message->receiver : $message->sender;
                $unread = \App\Models\Message::where('sender_id', $other->id)->where('receiver_id', Auth::id())->whereNull('read_at')->count();
            @endphp
            <a href="{{ route('messages.conversation', $other) }}" class="flex items-center gap-4 py-4 hover:bg-gray-50 px-2 rounded-lg transition">
                <div class="relative">
                    <img src="{{ $other->avatar_url }}" class="w-12 h-12 rounded-full object-cover">
                    @if($unread) <span class="absolute -top-1 -right-1 w-5 h-5 bg-indigo-600 text-white text-xs rounded-full flex items-center justify-center">{{ $unread }}</span> @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-center mb-0.5">
                        <span class="font-semibold {{ $unread ? 'text-indigo-700' : '' }}">{{ $other->name }}</span>
                        <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-500 truncate {{ $unread ? 'font-medium text-gray-700' : '' }}">
                        {{ $message->sender_id === Auth::id() ? 'Vous : ' : '' }}{{ Str::limit($message->body, 60) }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

{{-- resources/views/messages/conversation.blade.php --}}
@extends('layouts.app')
@section('title', 'Conversation avec ' . $user->name)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('messages.inbox') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
        <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full">
        <div>
            <div class="font-semibold">{{ $user->name }}</div>
            <div class="text-xs text-gray-400">{{ ucfirst($user->role) }}</div>
        </div>
        <a href="{{ route('profile.show', $user) }}" class="ml-auto text-xs text-indigo-600 hover:underline">Voir le profil</a>
    </div>

    {{-- Messages --}}
    <div class="space-y-3 mb-6 max-h-[60vh] overflow-y-auto" id="chat-box">
        @foreach($messages as $msg)
        @php $isMe = $msg->sender_id === Auth::id(); @endphp
        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} gap-2">
            @if(!$isMe) <img src="{{ $msg->sender->avatar_url }}" class="w-8 h-8 rounded-full self-end"> @endif
            <div class="{{ $isMe ? 'bg-indigo-600 text-white' : 'bg-white border' }} rounded-2xl px-4 py-2.5 max-w-xs shadow-sm">
                <p class="text-sm">{{ $msg->body }}</p>
                <div class="text-xs opacity-60 mt-1 text-right">{{ $msg->created_at->format('H:i') }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Reply Form --}}
    <div class="card">
        <form action="{{ route('messages.send', $user) }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="body" required
                   class="flex-1 border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 text-sm"
                   placeholder="Écrivez votre message...">
            <button type="submit" class="btn-primary px-4">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const box = document.getElementById('chat-box');
    if (box) box.scrollTop = box.scrollHeight;
</script>
@endpush
@endsection
