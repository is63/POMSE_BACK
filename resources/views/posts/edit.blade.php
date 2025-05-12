@extends('components.layout')

@section('main')
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Editar Post</h1>
        <form method="POST" action="{{ url('/posts/' . $post->id) }}" enctype="multipart/form-data"
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
                <div class="mb-4">
                    <label for="titulo" class="block text-gray-700 font-bold mb-2">Título:</label>
                    <input type="text" name="titulo" id="titulo" placeholder="Título"
                           value="{{ old('titulo', $post->titulo) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                           required>
                    @error('titulo')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" placeholder="Descripción"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">{{ old('descripcion', $post->descripcion) }}</textarea>
                    @error('descripcion')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="imagen" class="block text-gray-700 font-bold mb-2">Imagen:</label>
                    <div class="flex items-center space-x-4">
                        <input type="file" name="imagen" id="imagen" class="hidden" onchange="updateButtonText()">
                        <button type="button" id="uploadButton"
                                onclick="document.getElementById('imagen').click()"
                                class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Seleccionar Archivo
                        </button>
                    </div>
                    @if($post->imagen)
                        <p class="mt-4 text-gray-700 font-bold">Imagen actual:</p>
                        <img src="{{ asset($post->imagen) }}" alt="Imagen del post" class="my-2" width="200">
                    @endif
                    @error('imagen')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="search_usuario" class="block text-gray-700 font-bold mb-2">Buscar Usuario:</label>
                    <input type="text" id="search_usuario" placeholder="Ingrese el nombre del usuario"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           oninput="filterOptions('search_usuario', 'usuario_select')">

                    <label for="usuario_select" class="block text-gray-700 font-bold mb-2 mt-4">Usuario:</label>
                    <select name="usuario_id" id="usuario_select"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option
                                value="{{ $usuario->id }}" {{ $usuario->id == old('usuario_id', $post->usuario_id) ? 'selected' : '' }}>
                                {{ $usuario->usuario }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cambios
                </button>
                <a href="{{ url('/posts') }}"
                   class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
    <script>
        function updateButtonText() {
            const fileInput = document.getElementById('imagen');
            const uploadButton = document.getElementById('uploadButton');
            uploadButton.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Seleccionar Archivo';
        }
    </script>
    <x-search_script/>
@endsection
