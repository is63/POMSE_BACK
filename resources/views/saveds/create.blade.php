@extends('components.layout')

@section('main')
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">Guardar Post</h1>
        @session('error')
        <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endsession
        <form method="POST" action="{{ url('/saveds') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="bg-gray-50 px-4 py-3 rounded-t mb-6">
                <div class="mb-4">
                    <label for="usuario_id" class="block text-gray-700 font-bold mb-2">Usuario:</label>
                    <input list="usuarios" name="usuario_id" placeholder="Seleccione un usuario"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="usuarios">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->usuario }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-4">
                    <label for="post_id" class="block text-gray-700 font-bold mb-2">Post:</label>
                    <input list="posts" name="post_id" placeholder="Seleccione un post"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <datalist id="posts">
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}">{{ $post->titulo }}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Crear
                </button>
                <a href="{{ url('/saveds') }}"
                   class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
    <x-time_mensage/>
@endsection
