@if ($paginator->hasPages())
<nav class="flex flex-col sm:flex-row items-center sm:justify-between gap-4">

    {{-- Текст: "Показано 1–50 из 185" --}}
    <p class="text-sm text-gray-500">
        Показано
        <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
        из
        <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
    </p>

    {{-- Кнопки страниц --}}
    <div class="flex items-center gap-2 flex-wrap">

        {{-- Назад --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg
                         bg-gray-50 text-gray-300 border border-gray-200 cursor-not-allowed"
                  aria-disabled="true">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               rel="prev"
               class="inline-flex items-center justify-center w-11 h-11 rounded-lg
                      bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-50 transition-colors">
                ‹
            </a>
        @endif

        {{-- Номера страниц --}}
        @foreach ($elements as $element)

            {{-- Разделитель "…" --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-11 h-11 text-sm text-gray-400">
                    {{ $element }}
                </span>
            @endif

            {{-- Группа страниц --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg
                                     bg-indigo-600 text-white font-semibold text-sm"
                              aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-11 h-11 rounded-lg text-sm font-medium
                                  bg-indigo-50 text-indigo-700 border border-indigo-100 hover:bg-indigo-50 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Вперёд --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               rel="next"
               class="inline-flex items-center justify-center w-11 h-11 rounded-lg
                      bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-50 transition-colors">
                ›
            </a>
        @else
            <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg
                         bg-gray-50 text-gray-300 border border-gray-200 cursor-not-allowed"
                  aria-disabled="true">›</span>
        @endif

    </div>
</nav>
@endif
