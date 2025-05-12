@extends('components.layout')

@section('main')
    @props(['$friendship', '$usuarios'])

    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Editar Amistad</h1>
        <form method="POST" action="{{ url('friendships/' . $friendship->usuario_id . '/' . $friendship->amigo_id) }}"
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
                <div class="mb-4">
                    <label for="usuario_id" class="block text-gray-700 font-bold mb-2">Usuario_Id:</label>
                    <input list="usuarios" name="usuario_id" placeholder="Seleccione un usuario" disabled
                           value="{{ $friendship->usuario_id }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="usuarios">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-4">
                    <label for="amigo_id" class="block text-gray-700 font-bold mb-2">Amigo_Id:</label>
                    <input list="usuarios" name="amigo_id" placeholder="Seleccione un usuario" disabled
                           value="{{ $friendship->amigo_id }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="usuarios">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-700 font-bold">¿Solicitud Aceptada?</span>
                        <input type="checkbox" id="accepted" name="accepted" value="1" class="hidden peer"
                               @if($friendship->accepted) checked @endif>
                        <label for="accepted"
                               class="w-6 h-6 bg-gray-200 border-2 border-gray-300 rounded cursor-pointer peer-checked:bg-green-500 peer-checked:border-green-500 flex items-center justify-center">
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Actualizar
                </button>
                <a href="{{ url('/friendships') }}"
                   class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection

