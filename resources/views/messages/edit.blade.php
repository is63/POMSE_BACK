@extends('components.layout')

@section('main')
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Editar Mensaje</h1>
        <div method="POST" action="{{ route('messages.update', $message->id) }}" enctype="multipart/form-data"
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
                                {{ Illuminate\Support\Facades\DB::table('users')->where('id','=',$chat->participante_1)->pluck('usuario') }}
                                - {{ Illuminate\Support\Facades\DB::table('users')->where('id','=',$chat->participante_2)->pluck('usuario') }}
                            </option>
                        @endforeach
                    </select>
                    @error('chat_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="emisor_id" class="block text-gray-700 font-bold mb-2">Emisor:</label>
                    <input list="emisores" name="emisor_id" id="emisor_id" value="{{ $message->emisor_id }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="emisores">
                        <!-- Las opciones se llenarán dinámicamente -->
                    </datalist>
                    <p id="emisor_name" class="text-gray-600 text-sm mt-2"></p>
                    @error('emisor_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="receptor_id" class="block text-gray-700 font-bold mb-2">Receptor:</label>
                    <input list="receptores" name="receptor_id" id="receptor_id" value="{{ $message->receptor_id }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="receptores">
                        <!-- Las opciones se llenarán dinámicamente -->
                    </datalist>
                    <p id="receptor_name" class="text-gray-600 text-sm mt-2"></p>
                    @error('receptor_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="texto" class="block text-gray-700 font-bold mb-2">Texto:</label>
                    <textarea name="texto" id="texto" placeholder="Escribe tu mensaje"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">{{ old('texto', $message->texto) }}</textarea>
                    @error('texto')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="imagen" class="block text-gray-700 font-bold mb-2">Imagen (opcional):</label>
                    <input type="file" name="imagen" id="imagen"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                    @if($message->imagen)
                        <p class="my-4 text-gray-700 font-bold mb-2">Imagen actual: <img class="my-2"
                                                                                         src="{{ asset($message->imagen) }}"
                                                                                         width="200"
                                                                                         alt="Imagen del mensaje"></p>
                    @endif
                    @error('imagen')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
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
            function updateSelectedName(inputId, datalistId, displayId) {
                const input = document.getElementById(inputId);
                const datalist = document.getElementById(datalistId);
                const display = document.getElementById(displayId);

                const selectedOption = Array.from(datalist.options).find(option => option.value === input.value);
                display.textContent = selectedOption ? `Seleccionado: ${selectedOption.textContent}` : '';
            }

            document.getElementById('emisor_id').addEventListener('input', function () {
                updateSelectedName('emisor_id', 'emisores', 'emisor_name');
            });

            document.getElementById('receptor_id').addEventListener('input', function () {
                updateSelectedName('receptor_id', 'receptores', 'receptor_name');
            });

            document.getElementById('chat_id').addEventListener('change', function () {
                let chatId = this.value;
                if (chatId) {
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

            // Cargar los participantes del chat seleccionado al cargar la página
            document.addEventListener('DOMContentLoaded', function () {
                const chatId = document.getElementById('chat_id').value;
                if (chatId) {
                    document.getElementById('chat_id').dispatchEvent(new Event('change'));
                }
            });
        </script>

@endsection
