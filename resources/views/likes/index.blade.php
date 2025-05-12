@extends('components.layout')
@section('main')
    @props(['$table_name', '$table_data'])

    <!-- Contenedor principal con estilo de fondo, bordes redondeados y sombra -->
    <div class="bg-gray-100 rounded-lg shadow-md p-6">
        <!-- Título que muestra el nombre de la tabla -->
        <p class="text-2xl font-bold text-black text-center pb-4 border-b-2 border-b-black">Contenido de la tabla: <span
                class="uppercase ">{{ $table_name }} </span></p>
        @if(session('success'))
            <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <!-- Verifica si la tabla está vacía -->
        @if($table_data->isEmpty())
            <!-- Mensaje que indica que no hay datos en la tabla -->
            <p class="text-center text-gray-500 mt-4">La tabla está vacía.</p>
            <!-- Botón para crear -->
            <div class="mb-4 mt-8 ml-32 pr-36 text-center">
                <form method="get" action="{{ url('/likes/create') }}">
                    <button
                        class="bg-white hover:bg-green-500 text-green-500 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                        Crear
                    </button>
                </form>
            </div>
        @else
            <!-- Barra de búsqueda dinámica -->
            <x-search_bar :fields="array_keys((array) $table_data->first())"/>

            <!-- Contenedor para la tabla con scroll horizontal y altura fija -->
            <div class="overflow-auto mt-8 max-w-6xl mx-auto h-[550px]">
                <table class="min-w-full divide-y divide-black border border-gray-300 overflow-y-auto">
                    <thead class="bg-black text-white font-semibold sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3"></th>
                        @foreach($table_data->first() as $key => $value)
                            <th scope="col" class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                                {{ $key }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                    @foreach($table_data as $data)
                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-300">
                            <td class="px-6 py-4 text-gray-900 relative">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 6h16M4 12h16m-7 6h7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak @click.away="open = false"
                                         class="absolute left-0 top-6 w-32 bg-white border border-gray-300 rounded shadow-lg z-10">
                                        <ul class="py-1">
                                            <li>
                                                <form method="POST"
                                                      action="{{ url('likes/' . $data->usuario_id . '/' . $data->post_id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="block w-[100%] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Borrar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            @foreach($data as $key => $value)
                                <td data-field="{{ $key }}"
                                    class="px-6 py-4 text-md text-black max-w-[100ch] truncate">
                                    {{ $value }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Botón para crear -->
            <div class="mb-4 mt-8  pr-36 text-center">
                <a href="{{ url('/likes/create') }}"
                   class="bg-white hover:bg-green-500 text-green-500 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                    Crear
                </a>
            </div>
            <!-- Enlaces de paginación -->
            <div class="flex items-center justify-between mt-6">
                <!-- Página Actual -->
                <div class="text-gray-700">
                    Página {{ $table_data->currentPage() }} de {{ $table_data->lastPage() }}
                </div>

                <!-- Controles para cambiar de página -->
                <div class="flex items-center justify-center mt-6 space-x-2">
                    @php
                        $start = max(1, $table_data->currentPage() - 2);
                        $end = min($table_data->lastPage(), $table_data->currentPage() + 2);
                    @endphp

                    @if ($table_data->onFirstPage())
                        <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded">Anterior</span>
                    @else
                        <a href="{{ $table_data->previousPageUrl() }}"
                           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Anterior</a>
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $table_data->currentPage())
                            <span class="px-4 py-2 bg-blue-500 text-white rounded">{{ $i }}</span>
                        @else
                            <a href="{{ $table_data->url($i) }}"
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">{{ $i }}</a>
                        @endif
                    @endfor

                    @if ($table_data->hasMorePages())
                        <a href="{{ $table_data->nextPageUrl() }}"
                           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Siguiente</a>
                    @else
                        <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded">Siguiente</span>
                    @endif
                </div>

                <div>
                    <input type="text" placeholder="Ir a página..." class="px-4 py-2 border border-gray-300 rounded"
                           onkeydown="if(event.key === 'Enter') window.location.href='?page=' + this.value">
                </div>
            </div>
        @endif
    </div>
    <x-time_mensage/>
@endsection
