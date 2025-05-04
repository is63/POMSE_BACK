@props(['table_name', 'table_data'])

<!-- Contenedor principal con estilo de fondo, bordes redondeados y sombra -->
<div class="bg-gray-100 rounded-lg shadow-md p-6">
    <!-- Título que muestra el nombre de la tabla -->
    <p class="text-2xl font-bold text-gray-800 text-center">Contenido de la tabla: {{ $table_name }}</p>

    <!-- Verifica si la tabla está vacía -->
    @if($table_data->isEmpty())
        <!-- Mensaje que indica que no hay datos en la tabla -->
        <p class="text-center text-gray-500 mt-4">La tabla está vacía.</p>
    @else
        <!-- Contenedor para la tabla con scroll horizontal y altura fija -->
        <div class="overflow-auto mt-8 max-w-6xl mx-auto h-[550px]">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 overflow-y-auto">
                <thead class="bg-gray-50">
                <tr>
                    <!-- Celda vacía para alinear correctamente los valores con sus claves -->
                    <th class="px-6 py-3"></th>
                    <!-- Genera dinámicamente las claves como encabezados de la tabla -->
                    @foreach($table_data->first() as $key => $value)
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                            {{ $key }}
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <!-- Itera sobre los datos de la tabla para generar las filas -->
                @foreach($table_data as $data)
                    <tr class="odd:bg-gray-100 even:bg-white">
                        <!-- Columna con el botón de menú desplegable -->
                        <td class="px-6 py-4 text-gray-900 relative">
                            <div x-data="{ open: false }" class="relative">
                                <!-- Botón de hamburguesa para abrir el menú -->
                                <button @click="open = !open" class="focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6h16M4 12h16m-7 6h7"/>
                                    </svg>
                                </button>
                                <!-- Menú desplegable con opciones -->
                                <div x-show="open" x-cloak @click.away="open = false"
                                     class="absolute left-0 mt-2 w-32 bg-white border border-gray-300 rounded shadow-lg z-10">
                                    <ul class="py-1">
                                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Editar</a>
                                        </li>
                                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Borrar</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <!-- Genera dinámicamente las celdas con los valores de la fila -->
                        @foreach($data as $value)
                            <td class="px-6 py-4 {{ $table_name === 'users' ? 'text-sm' : 'text-lg' }} text-gray-900">
                                {{ $value }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- Enlaces de paginación -->
        <div class="m-12">
            {{ $table_data->links() }}
        </div>
        <!-- Botón para realizar una acción (crear) -->
        <div class="mt-6 text-center">
            <a href="{{ url('/some-action') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Crear
            </a>
        </div>
    @endif
</div>
