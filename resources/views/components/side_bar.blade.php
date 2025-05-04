<div class="flex">

    <aside class="w-64 bg-gray-800 text-white h-[93vh]">
        <div class="p-4">
            <h2 class="text-lg font-bold justify-around">Tablas de la Base de Datos</h2>
            <ul class="mt-4 space-y-2 overflow-y-auto">
                @foreach ($tables as $table)
                    <li>
                        <a href="{{ url('/table/' . $table->TABLE_NAME) }}"
                           class="block px-4 py-2 rounded hover:bg-gray-700">
                            {{ $table->TABLE_NAME }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>
