@extends('components.layout')

@section('main')
    @props(['$usuarios'])
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Crear Nueva Amistad</h1>
        <form method="POST" action="{{ url('/friendships') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
            <div class="mb-4">
                <label for="search_usuario_id" class="block text-gray-700 font-bold mb-2">Buscar Usuario:</label>
                <input type="text" id="search_usuario_id" placeholder="Ingrese el nombre del usuario"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       oninput="filterOptions('search_usuario_id', 'usuario_id_select')">

                <label for="usuario_id_select" class="block text-gray-700 font-bold mb-2 mt-4">Usuario_Id:</label>
                <select name="usuario_id" id="usuario_id_select"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="search_amigo_id" class="block text-gray-700 font-bold mb-2">Buscar Amigo:</label>
                <input type="text" id="search_amigo_id" placeholder="Ingrese el nombre del usuario"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       oninput="filterOptions('search_amigo_id', 'amigo_id_select')">

                <label for="amigo_id_select" class="block text-gray-700 font-bold mb-2 mt-4">Amigo_Id:</label>
                <select name="amigo_id" id="amigo_id_select"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 mt-8">
                <div class="flex items-center space-x-2">
                    <span class="text-gray-700 font-bold">¿Solicitud Aceptada?</span>
                    <input type="checkbox" id="accepted" name="accepted" class="hidden peer" value="1">
                    <label for="accepted"
                           class="w-6 h-6 bg-gray-200 border-2 border-gray-300 rounded cursor-pointer peer-checked:bg-green-500 peer-checked:border-green-500 flex items-center justify-center">
                    </label>
                </div>
            </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Crear
                </button>
                <a href="{{ url('/friendships') }}"
                   class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>

    </div>
    <x-search_script/>
@endsection
