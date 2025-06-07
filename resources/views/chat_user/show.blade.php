@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chat Participant</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">User: {{ $chatUser->user_name ?? $chatUser->user_id }}</h5>
            <p class="card-text">Chat: {{ $chatUser->chat_name ?? $chatUser->chat_id }}</p>
            <p class="card-text">Added: {{ $chatUser->created_at }}</p>
            <a href="{{ route('chat_user.edit', $chatUser->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('chat_user.destroy', $chatUser->id) }}" method="POST" style="display:inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
            <a href="{{ route('chat_user.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
