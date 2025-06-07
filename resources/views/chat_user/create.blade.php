@extends('components.layout')

@section('main')
    <div class="max-w-2xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Crear Relación Chat-Usuario</h1>
        <form method="POST" action="{{ route('chat_user.store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Chat:</label>
                <select name="chat_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100" required>
                    <option value="">Seleccione un chat</option>
                    @foreach($chats as $chat)
                        <option value="{{ $chat->id }}">{{ $chat->name ?? 'Chat #' . $chat->id }}</option>
                    @endforeach
                </select>
                @error('chat_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Usuario:</label>
                <select name="user_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100" required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->usuario }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between mt-4">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Crear</button>
                <a href="{{ route('chat_user.index') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancelar</a>
            </div>
        </form>
    </div>
@endsection