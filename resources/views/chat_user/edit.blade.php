@extends('components.layout')

@section('main')
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Editar Relación Chat-Usuario</h1>
        <form method="POST"
            action="{{ route('chat_user.update', ['chat_id' => $chatUser->chat_id, 'user_id' => $chatUser->user_id]) }}"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
                @if(session('success'))
                    <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-4">
                    <label for="user_id" class="block text-gray-700 font-bold mb-2">Usuario:</label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100"
                        id="user_id" name="user_id" required>
                        <option value="">Seleccione un usuario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $chatUser->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->usuario }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="chat_id" class="block text-gray-700 font-bold mb-2">Chat (ID):</label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100"
                        id="chat_id" name="chat_id" required>
                        <option value="">Seleccione un chat</option>
                        @foreach ($chats as $chat)
                            <option value="{{ $chat->id }}" {{ $chatUser->chat_id == $chat->id ? 'selected' : '' }}>
                                {{ $chat->id }}@if($chat->name) | {{ $chat->name }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-between mt-8">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cambios
                </button>
                <a href="{{ route('chat_user.index') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection