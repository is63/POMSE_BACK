{{-- filepath: resources/views/vendor/pagination/tailwind-blue.blade.php --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center">
        <ul class="inline-flex items-center -space-x-px">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="px-3 py-2 ml-0 leading-tight text-blue-400 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">Anterior</span>
                </li>
            @else
                <li>
                    <button wire:click="previousPage" wire:loading.attr="disabled"
                        class="px-3 py-2 ml-0 leading-tight text-blue-600 bg-white border border-gray-300 rounded-l-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        Anterior
                    </button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-2 leading-tight text-white bg-blue-600 border border-blue-600">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <button wire:click="gotoPage({{ $page }})"
                                    class="px-3 py-2 leading-tight text-blue-600 bg-white border border-gray-300 hover:bg-blue-100 hover:text-blue-700 transition">
                                    {{ $page }}
                                </button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <button wire:click="nextPage" wire:loading.attr="disabled"
                        class="px-3 py-2 leading-tight text-blue-600 bg-white border border-gray-300 rounded-r-lg hover:bg-blue-100 hover:text-blue-700 transition">
                        Siguiente
                    </button>
                </li>
            @else
                <li>
                    <span
                        class="px-3 py-2 leading-tight text-blue-400 bg-white border border-gray-300 rounded-r-lg cursor-not-allowed">Siguiente</span>
                </li>
            @endif
        </ul>
    </nav>
@endif