@extends('components.layout')

@section('main')
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Crear Nuevo Mensaje</h1>
        @session('error')
        <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endsession
        <form method="POST" action="{{ url('/messages') }}" enctype="multipart/form-data"
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
                <div class="mb-4">
                    <label for="chat_id" class="block text-gray-700 font-bold mb-2">Chat:</label>
                    <select name="chat_id" id="chat_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Seleccione un chat</option>
                        @foreach($chats as $chat)
                            <option value="{{ $chat->id }}">
                                {{ DB::table('users')->where('id','=',$chat->participante_1)->pluck('usuario')[0] }}
                                - {{ DB::table('users')->where('id','=',$chat->participante_2)->pluck('usuario')[0] }}
                            </option>
                        @endforeach
                    </select>
                    @error('chat_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="emisor_id" class="block text-gray-700 font-bold mb-2">Emisor:</label>
                    <input list="emisores" name="emisor_id" id="emisor_id" placeholder="Seleccione un emisor"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="emisores">
                        <!-- Las opciones se llenarán dinámicamente -->
                    </datalist>
                    @error('emisor_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="receptor_id" class="block text-gray-700 font-bold mb-2">Receptor:</label>
                    <input list="receptores" name="receptor_id" id="receptor_id" placeholder="Seleccione un receptor"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="receptores">
                        <!-- Las opciones se llenarán dinámicamente -->
                    </datalist>
                    @error('receptor_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="texto" class="block text-gray-700 font-bold mb-2">Texto:</label>
                    <textarea name="texto" id="texto" placeholder="Escribe tu mensaje"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">{{ old('texto') }}</textarea>
                    @error('texto')
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
                    @error('imagen')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Crear
                </button>
                <a href="{{ url('/messages') }}"
                   class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('chat_id').addEventListener('change', function () {
            let chatId = this.value;
            if (chatId) {
                // Hacer una solicitud para obtener los participantes del chat
                fetch(`/chats/${chatId}/participants`)
                    .then(response => response.json())
                    .then(data => {
                        let emisorDatalist = document.getElementById('emisores');
                        let receptorDatalist = document.getElementById('receptores');

                        // Limpiar las opciones previas
                        emisorDatalist.innerHTML = '';
                        receptorDatalist.innerHTML = '';

                        // Llenar las opciones con los participantes
                        data.participants.forEach(user => {
                            let emisorOption = document.createElement('option');
                            emisorOption.value = user.id;
                            emisorOption.textContent = user.usuario;
                            emisorDatalist.appendChild(emisorOption);

                            let receptorOption = document.createElement('option');
                            receptorOption.value = user.id;
                            receptorOption.textContent = user.usuario;
                            receptorDatalist.appendChild(receptorOption);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Validar que el valor ingresado en el input coincida con una opción del datalist
        function validateDatalistInput(inputId, datalistId) {
            const input = document.getElementById(inputId);
            const datalist = document.getElementById(datalistId);
            const options = Array.from(datalist.options).map(option => option.value);

        }

        // Añadir validación al perder el foco en los inputs
        document.getElementById('emisor_id').addEventListener('blur', function () {
            validateDatalistInput('emisor_id', 'emisores');
        });

        document.getElementById('receptor_id').addEventListener('blur', function () {
            validateDatalistInput('receptor_id', 'receptores');
        });

        function updateButtonText() {
            let fileInput = document.getElementById('imagen');
            let uploadButton = document.getElementById('uploadButton');
            uploadButton.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Seleccionar Archivo';
        }
    </script>
    <x-time_mensage/>
@endsection
