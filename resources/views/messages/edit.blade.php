@extends('components.layout')

@section('main')
@php 
$users  = Illuminate\Support\Facades\DB::table('users')->get();;
@endphp
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Editar Mensaje</h1>
        <form method="POST" action="{{ url('/messages', $message->id) }}" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
                <div class="mb-4">
                    <label for="chat_id" class="block text-gray-700 font-bold mb-2">Chat:</label>
                    <select name="chat_id" id="chat_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($chats as $chat)
                            <option value="{{ $chat->id }}" {{ $chat->id == $message->chat_id ? 'selected' : '' }}>
                                Chat #{{ $chat->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('chat_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="user_id" class="block text-gray-700 font-bold mb-2">Usuario (Remitente):</label>
                    <select name="user_id" id="user_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $message->user_id ? 'selected' : '' }}>
                                {{ $user->usuario }} (ID: {{ $user->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-bold mb-2">Contenido:</label>
                    <textarea name="content" id="content" placeholder="Escribe tu mensaje"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $message->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image_path" class="block text-gray-700 font-bold mb-2">Imagen (opcional):</label>
                    <div class="flex items-center space-x-4">
                        <input type="file" name="image_path" id="image_path" class="hidden" onchange="updateButtonText()">
                        <button type="button" id="uploadButton" onclick="document.getElementById('image_path').click()"
                            class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Seleccionar Archivo
                        </button>
                    </div>
                    @if($message->image_path)
                        <p class="my-4 text-gray-700 font-bold mb-2">Imagen actual: <img class="my-2"
                                src="{{ asset($message->image_path) }}" width="200" alt="Imagen del mensaje"></p>
                    @endif
                    @error('image_path')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cambios
                </button>
                <a href="{{ url('/messages') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        function updateButtonText() {
            const fileInput = document.getElementById('image_path');
            const uploadButton = document.getElementById('uploadButton');
            uploadButton.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Seleccionar Archivo';
        }
    </script>

@endsection