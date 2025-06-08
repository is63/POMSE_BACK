<div>
    <!-- Título que muestra el nombre de la tabla -->
    <p class="text-2xl font-bold text-black text-center pb-4 border-b-2 border-b-black">Contenido de la tabla: <span
            class="uppercase">{{ $tableName }} </span></p>
    @if(session('success'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex justify-center gap-2 mb-4 mt-12">
        <input type="text"
            class="w-[90vh] rounded-md border border-gray-300 px-4 py-2 text-center focus:outline-none focus:ring-2 focus:ring-green-500"
            placeholder="Buscar en la tabla" wire:model.live="condition" wire:keydown="search" />
        @if (isset($columns) && count($columns) > 0)
            <select wire:model="type" wire:change="search"
                class="rounded-md border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach ($columns as $column)
                    @if(!in_array($column, ['email_verified_at', 'remember_token', 'created_at', 'updated_at', 'password', 'texto', 'imagen']))
                        <option value="{{ $column }}" {{ $column == "usuario" ? "selected" : "" }}>{{ $column }}</option>
                    @endif
                @endforeach
            </select>
        @endif
    </div>

    <div>
        @if ($tableData->count() > 0)
            <div class="overflow-auto mt-8 max-w-6xl mx-auto h-[550px]">
                <table class="min-w-full divide-y divide-black border border-gray-300 overflow-y-auto">
                    <thead class="bg-black text-white font-semibold sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3"></th>
                            @foreach($columns as $column)
                                @if(!in_array($column, ['email_verified_at', 'remember_token']))
                                    <th scope="col" class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                                        {{ $column }}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @foreach($tableData as $data)
                            <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-300">
                                <td class="px-6 py-4 text-gray-900 relative">
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 12h16m-7 6h7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-cloak @click.away="open = false"
                                            class="absolute left-0 top-6 w-32 bg-white border border-gray-300 rounded shadow-lg z-10">
                                            <ul class="py-1">
                                                <li>
                                                    @if(!in_array($tableName, ['saveds', 'likes', 'friendships', 'messages']))
                                                        @if($tableName === 'chat_user')
                                                            <a href="/{{ $tableName }}/{{ $data->chat_id }}/{{ $data->user_id }}/edit"
                                                                class="block px-4 text-center py-2 text-sm text-gray-700 hover:bg-gray-100">Editar</a>
                                                        @else
                                                            <a href="/{{ $tableName}}/{{ $data->id }}/edit"
                                                                class="block px-4 text-center py-2 text-sm text-gray-700 hover:bg-gray-100">Editar</a>
                                                        @endif
                                                    @endif
                                                </li>
                                                <li>
                                                    @if($tableName === 'chat_user')
                                                        <form method="POST"
                                                            action="/{{ $tableName }}/{{ $data->chat_id }}/{{ $data->user_id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-[100%] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Borrar</button>
                                                        </form>
                                                    @elseif($tableName === 'saveds' || $tableName === 'likes')
                                                        <form method="POST"
                                                            action="/{{ $tableName }}/{{ $data->usuario_id }}/{{ $data->post_id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-[100%] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Borrar</button>
                                                        </form>
                                                    @elseif($tableName === 'friendships')
                                                        <form method="POST"
                                                            action="/friendships/{{ $data->usuario_id }}/{{ $data->amigo_id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-[100%] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Borrar</button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="/users/{{ $data->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-[100%] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Borrar</button>
                                                        </form>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                @foreach($columns as $column)
                                    @if(!in_array($column, ['email_verified_at', 'remember_token']))
                                        @if($column === 'usuario_id' || $column === 'amigo_id' || $column === 'receptor_id' || $column === 'emisor_id')
                                            <td data-field="{{ $column }}" class="px-6 py-4 text-sm text-black max-w-[100] truncate">
                                                <span class="font-semibold">
                                                    {{ optional(Illuminate\Support\Facades\DB::table('users')->where('id', $data->$column)->first())->usuario ?? 'Sin usuario' }}
                                                </span>
                                                <br>Id: {{ $data->$column }}
                                            </td>
                                        @elseif($column === 'chat_id')
                                            @php
                                                $participantes = Illuminate\Support\Facades\DB::table('chat_user')
                                                    ->join('users', 'chat_user.user_id', '=', 'users.id')
                                                    ->where('chat_user.chat_id', $data->$column)
                                                    ->pluck('users.usuario')
                                                    ->toArray();
                                            @endphp
                                            <td data-field="{{ $column }}" class="px-6 py-4 text-sm text-black max-w-[100] truncate">
                                                <span class="font-semibold">
                                                    [{{ implode(' - ', $participantes) }}]
                                                </span>
                                                <br>Id: {{ $data->$column }}
                                            </td>
                                        @elseif($column === 'post_id')
                                            <td data-field="{{ $column }}" class="px-6 py-4 text-sm text-black max-w-[100] truncate">
                                                <span class="font-semibold">
                                                    {{ optional(Illuminate\Support\Facades\DB::table('posts')->where('id', $data->$column)->first())->titulo ?? 'Sin post' }}
                                                </span>
                                                <br>Id: {{ $data->$column }}
                                            </td>
                                        @else
                                            <td data-field="{{ $column }}" class="px-6 py-4 text-sm text-black max-w-[100] truncate">
                                                {{ $data->$column }}
                                            </td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center mt-8">
                {{ $tableData->links('livewire::tailwind-blue') }}
            </div>
        @else
            <p class="text-center text-gray-500 mt-4">La tabla está vacía.</p>
        @endif
    </div>
    <!-- Botón para crear -->
    <div class="mb-4 mt-8 text-center">
        <form method="get" action="{{ url('/' . $tableName . '/create') }}">
            <button
                class="bg-white hover:bg-green-500 text-green-500 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                Crear
            </button>
        </form>
    </div>
</div>