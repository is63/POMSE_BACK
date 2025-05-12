@extends('components.layout')

@section('main')
    @props(['usuarios', 'posts'])
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Crear Nuevo Comentario</h1>

        @session('error')
        <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endsession

        <form method="POST" action="{{ url('/comments') }}" enctype="multipart/form-data"
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">

                <!-- Campo Texto (contenido del comentario) -->
                <div class="mb-4">
                    <label for="texto" class="block text-gray-700 font-bold mb-2">Comentario:</label>
                    <textarea name="texto" id="texto" placeholder="Escribe tu comentario"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                              required>{{ old('texto') }}</textarea>
                    @error('texto')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Imagen (opcional) -->
                <div class="mb-4">
                    <label for="imagen" class="block text-gray-700 font-bold mb-2">Imagen (opcional):</label>
                    <div class="flex items-center space-x-4">
                        <input type="file" name="imagen" id="imagen" class="hidden" onchange="updateButtonText()">
                        <button type="button" id="uploadButton"
                                onclick="document.getElementById('imagen').click()"
                                class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Seleccionar Archivo
                        </button>
                    </div>
                    @error('imagen')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Usuario -->
                <div class="mb-4">
                    <label for="search_usuario" class="block text-gray-700 font-bold mb-2">Buscar Usuario:</label>
                    <input type="text" id="search_usuario" placeholder="Ingrese el nombre del usuario"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           oninput="filterOptions('search_usuario', 'usuario_id')">

                    <label for="usuario_id" class="block text-gray-700 font-bold mb-2 mt-4">Usuario:</label>
                    <select name="usuario_id" id="usuario_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                        @endforeach
                    </select>
                    @error('usuario_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Post -->
                <div class="mb-4">
                    <label for="search_post" class="block text-gray-700 font-bold mb-2">Buscar Post:</label>
                    <input type="text" id="search_post" placeholder="Ingrese el título del post"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           oninput="filterOptions('search_post', 'post_id')">

                    <label for="post_id" class="block text-gray-700 font-bold mb-2 mt-4">Post:</label>
                    <select name="post_id" id="post_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Seleccione un post</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}">{{ $post->titulo }}</option>
                        @endforeach
                    </select>
                    @error('post_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Crear Comentario
                </button>
                <a href="{{ url('/comments') }}"
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
    <script>
        function filterOptions(inputId, selectId) {
            const input = document.getElementById(inputId).value.toLowerCase();
            const select = document.getElementById(selectId);
            const options = select.options;

            let hasVisibleOptions = false;

            // Recorre todas las opciones (comienza desde 1 para ignorar el placeholder)
            for (let i = 1; i < options.length; i++) {
                const optionText = options[i].text.toLowerCase(); // Texto visible de la opción
                const optionValue = options[i].value.toLowerCase(); // Valor de la opción

                // Condición de filtrado (si no hay input, o si el texto incluye el valor de búsqueda)
                if (input === '' || optionText.includes(input) || optionValue.includes(input)) {
                    options[i].style.display = ''; // Mostrar opción
                    hasVisibleOptions = true;
                } else {
                    options[i].style.display = 'none'; // Ocultar opción
                }
            }

            // Si no hay opciones visibles, restablecer el valor del select
            if (!hasVisibleOptions) {
                select.value = ''; // Resetear valor del select
            }
        }
    </script>
    <x-time_mensage/>
@endsection
