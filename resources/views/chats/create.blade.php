@extends('components.layout')

@section('main')
    @props(['usuarios'])
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Crear Nuevo Chat</h1>

        @if(session('error'))
            <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/chats') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nombre del chat (opcional):</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Participante 1:</label>
                <input type="text" id="search_participante_1" placeholder="Buscar usuario..."
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-2 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                    oninput="filterOptions('search_participante_1', 'participante_1')">
                <select name="participantes[]" id="participante_1" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                    <option value="">Seleccione un usuario</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                    @endforeach
                </select>
                @error('participantes.0')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Participante 2:</label>
                <input type="text" id="search_participante_2" placeholder="Buscar usuario..."
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-2 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                    oninput="filterOptions('search_participante_2', 'participante_2')">
                <select name="participantes[]" id="participante_2" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                    <option value="">Seleccione un usuario</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                    @endforeach
                </select>
                @error('participantes.1')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between mt-4">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Crear Chat
                </button>
                <a href="{{ url('/chats') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
    <script>
        function filterOptions(inputId, selectId) {
            const input = document.getElementById(inputId).value.toLowerCase();
            const select = document.getElementById(selectId);
            const options = select.options;
            for (let i = 1; i < options.length; i++) {
                const optionText = options[i].text.toLowerCase();
                const optionValue = options[i].value.toLowerCase();
                options[i].style.display = (input === '' || optionText.includes(input) || optionValue.includes(input)) ? '' : 'none';
            }
        }
    </script>
@endsection