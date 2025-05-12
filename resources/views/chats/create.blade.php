@extends('components.layout')

@section('main')
    @props(['usuarios'])
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Crear Nuevo Chat</h1>

        @session('error')
        <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endsession

        <form method="POST" action="{{ url('/chats') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">

                <!-- Campo Participante 1 -->
                <div class="mb-4">
                    <label for="search_participante_1" class="block text-gray-700 font-bold mb-2">Buscar Participante
                        1:</label>
                    <input type="text" id="search_participante_1" placeholder="Ingrese el nombre del usuario"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           oninput="filterOptions('search_participante_1', 'participante_1')">

                    <label for="participante_1" class="block text-gray-700 font-bold mb-2 mt-4">Participante 1:</label>
                    <select name="participante_1" id="participante_1"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                        @endforeach
                    </select>
                    @error('participante_1')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Participante 2 -->
                <div class="mb-4">
                    <label for="search_participante_2" class="block text-gray-700 font-bold mb-2">Buscar Participante
                        2:</label>
                    <input type="text" id="search_participante_2" placeholder="Ingrese el nombre del usuario"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           oninput="filterOptions('search_participante_2', 'participante_2')">

                    <label for="participante_2" class="block text-gray-700 font-bold mb-2 mt-4">Participante 2:</label>
                    <select name="participante_2" id="participante_2"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                        @endforeach
                    </select>
                    @error('participante_2')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

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

<x-search_script/>
@endsection
