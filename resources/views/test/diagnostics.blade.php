<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Диагностика
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Прогресс-блок --}}
            <div class="bg-white shadow-sm rounded-2xl p-6 mb-8 text-center">
                @if($session->name)
                <p class="text-gray-500 mb-1">Привет, <span class="font-semibold text-gray-900">{{ $session->name }}</span>!</p>
                @endif
                <p class="text-gray-500 text-sm">Пройди все блоки — это займёт около 30–35 минут</p>

                @php
                    $testBlocksCount    = $blocks->where('slug', '!=', 'anketa')->count();
                    $completedTestCount = $blocks->where('slug', '!=', 'anketa')
                        ->filter(fn($b) => in_array($b->slug, $completedSlugs))->count();
                    $progressPercent    = $testBlocksCount > 0
                        ? round($completedTestCount / $testBlocksCount * 100) : 0;
                @endphp

                <div class="w-full bg-gray-100 rounded-full h-2.5 mt-5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $progressPercent }}%; background-color:#1B52CC;"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    {{ $completedTestCount }} из {{ $testBlocksCount }} тестов пройдено
                </p>
            </div>

            {{-- Сетка карточек --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

                @foreach($blocks as $block)
                @php
                    $isDone     = in_array($block->slug, $completedSlugs);
                    $iconFill   = $isDone ? '#dcfce7' : '#d7e0ff';
                    $iconStroke = $isDone ? '#3cb061' : '#4147d5';
                @endphp

                <div class="group flex flex-col bg-white rounded-2xl p-6 transition-all duration-200
                    hover:-translate-y-1 hover:shadow-lg
                    {{ $isDone
                        ? 'border border-green-200 shadow-sm'
                        : 'border border-gray-100 shadow-sm hover:border-blue-100' }}">

                    {{-- Иконка + статус --}}
                    <div class="flex items-start justify-between mb-5">
                        @if($block->slug === 'intellect')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 shrink-0" viewBox="0 0 14 14">
                            <path d="M0 0h14v14H0z" fill="none" />
                            <g fill="none">
                                <path fill="{{ $iconFill }}" d="M9.38 1.438A2.38 2.38 0 0 0 7 3.818v6.47a2.724 2.724 0 1 0 5.439-.228c.567-.61.924-1.8.924-2.795c0-1.354-.664-2.67-1.616-3.189q.013-.126.013-.258a2.38 2.38 0 0 0-2.38-2.38m-4.76 0A2.38 2.38 0 0 1 7 3.818v6.47a2.724 2.724 0 1 1-5.439-.228C.994 9.45.636 8.26.636 7.265c0-1.354.665-2.671 1.617-3.189A2.38 2.38 0 0 1 4.62 1.438" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M4.62 1.438A2.38 2.38 0 0 1 7 3.818v6.47a2.724 2.724 0 1 1-5.439-.228C.994 9.45.636 8.26.636 7.265c0-1.354.665-2.671 1.617-3.189A2.38 2.38 0 0 1 4.62 1.438" stroke-width="0.5" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M3.511 5.694c-.35-.08-1.141-.599-1.26-1.611M4.97 8.365C6.398 8.205 6.963 6.699 7 6.086M2.29 8.632c-.356.28-.64.917-.731 1.427M9.38 1.438A2.38 2.38 0 0 0 7 3.818v6.47a2.724 2.724 0 1 0 5.439-.228c.567-.61.924-1.8.924-2.795c0-1.354-.664-2.67-1.616-3.189q.013-.126.013-.258a2.38 2.38 0 0 0-2.38-2.38" stroke-width="0.5" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M10.489 5.694c.35-.08 1.141-.599 1.26-1.611M9.03 8.365C7.602 8.205 7.037 6.699 7 6.086m4.71 2.546c.356.28.64.917.731 1.427" stroke-width="0.5" />
                            </g>
                        </svg>
                        @elseif($block->slug === 'sklon')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 shrink-0" viewBox="0 0 14 14">
                            <path d="M0 0h14v14H0z" fill="none" />
                            <g fill="none">
                                <path fill="{{ $iconFill }}" fill-rule="evenodd" d="M7 13.5a6.5 6.5 0 0 0 5.674-9.674L11.5 5l-2-.5l-.5-2l1.174-1.174A6.5 6.5 0 1 0 7 13.5m4.438-11.25q.16.15.311.312L11.5 2.5z" clip-rule="evenodd" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M13.48 7.5a6.5 6.5 0 1 1-6.93-7" stroke-width="0.7" />
                                <path fill="#fff" d="m11.5 5l-2-.5l-.5-2l2-2l.5 2l2 .5z" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="m11.5 5l-2-.5l-.5-2l2-2l.5 2l2 .5zM9.79 8.09A3 3 0 1 1 5.9 4.21M7 7l2.5-2.5" stroke-width="0.7" />
                            </g>
                        </svg>
                        @elseif($block->slug === 'values')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 shrink-0" viewBox="0 0 14 14">
                            <path d="M0 0h14v14H0z" fill="none" />
                            <g fill="none">
                                <path fill="{{ $iconFill }}" d="M10.638 1.54H3.362a1.07 1.07 0 0 0-.85.46L.692 4.52a1.05 1.05 0 0 0 .06 1.29L6.21 12.1a1 1 0 0 0 1.58 0l5.457-6.29a1.05 1.05 0 0 0 .06-1.29L11.488 2a1.07 1.07 0 0 0-.85-.46" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M10.638 1.54H3.362a1.07 1.07 0 0 0-.85.46L.692 4.52a1.05 1.05 0 0 0 .06 1.29L6.21 12.1a1 1 0 0 0 1.58 0l5.457-6.29a1.05 1.05 0 0 0 .06-1.29L11.488 2a1.07 1.07 0 0 0-.85-.46" stroke-width="0.5" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M6.48 1.53L4.04 5.31L7 12.46m.55-10.93l2.43 3.78L7 12.46M.52 5.31h12.96" stroke-width="0.5" />
                            </g>
                        </svg>
                        @elseif($block->slug === 'anketa')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 shrink-0" viewBox="0 0 14 14">
                            <path d="M0 0h14v14H0z" fill="none" />
                            <g fill="none">
                                <path fill="{{ $iconFill }}" fill-rule="evenodd" d="M4.5 1.5H2.75a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8.5a1 1 0 0 0 1-1v-10a1 1 0 0 0-1-1H9.5V2a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1z" clip-rule="evenodd" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M9.75 1.5h1.5a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-8.5a1 1 0 0 1-1-1v-10a1 1 0 0 1 1-1h1.5" stroke-width="0.7" />
                                <path fill="#fff" d="M8.5.5h-3a1 1 0 0 0-1 1V2a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-.5a1 1 0 0 0-1-1" />
                                <path stroke="{{ $iconStroke }}" stroke-linecap="round" stroke-linejoin="round" d="M8.5.5h-3a1 1 0 0 0-1 1V2a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-.5a1 1 0 0 0-1-1M5 9l1.5 1l3-4" stroke-width="0.7" />
                            </g>
                        </svg>
                        @else
                        <span class="text-4xl leading-none select-none">{{ $block->icon }}</span>
                        @endif

                        @if($isDone)
                        <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium
                                     bg-green-50 text-green-700 border border-green-200">
                            ✓ Пройден
                        </span>
                        @else
                        <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full
                                     bg-gray-50 text-gray-400 border border-gray-200">
                            Не пройден
                        </span>
                        @endif
                    </div>

                    {{-- Название --}}
                    <h3 class="font-bold text-gray-900 text-base mb-2 leading-snug">
                        {{ $block->name }}
                    </h3>

                    {{-- Описание --}}
                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ $block->description }}
                    </p>

                    {{-- Время --}}
                    @if($block->slug !== 'anketa')
                    <p class="text-xs text-gray-400 mt-2">
                        ⏱ {{ $block->estimated_minutes }} мин.
                    </p>
                    @endif

                    {{-- Кнопка --}}
                    <div class="mt-auto pt-5">
                        @if($block->slug === 'anketa')
                            <a href="{{ route('test.anketa.edit') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-xl border transition-all duration-150
                               {{ $isDone
                                   ? 'border-green-200 text-green-700 hover:bg-green-50'
                                   : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                ✏️ Изменить
                            </a>
                        @elseif($isDone)
                            <form method="POST" action="{{ route('test.block.reset', $block->slug) }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-all duration-150">
                                    ↩ Пройти заново
                                </button>
                            </form>
                        @else
                            <a href="{{ route('test.question', [$block->slug, 1]) }}"
                               class="inline-flex items-center gap-1.5 px-5 py-2.5 text-white text-sm font-semibold rounded-xl shadow-sm hover:opacity-90 transition-all duration-150"
                               style="background-color:#1B52CC;">
                                Пройти →
                            </a>
                        @endif
                    </div>
                </div>

                @endforeach
            </div>

            {{-- Кнопка результатов --}}
            <div class="text-center">
                @if(in_array('sklon', $completedSlugs))
                    <a href="{{ route('test.results', $session->token) }}"
                       class="inline-flex items-center gap-2 px-8 py-4 text-white text-base font-semibold rounded-xl hover:opacity-90 transition shadow-lg"
                       style="background-color:#1B52CC;">
                        @if($allDone)
                            🎯 Перейти к результатам
                        @else
                            📊 Посмотреть частичные результаты
                        @endif
                    </a>
                    @if(!$allDone)
                    <p class="text-xs text-gray-400 mt-2">
                        Результаты будут точнее после прохождения всех блоков
                    </p>
                    @endif
                @else
                    <button disabled
                        class="inline-flex items-center gap-2 px-8 py-4 bg-gray-100 text-gray-400 text-base font-semibold rounded-xl cursor-not-allowed border border-gray-200">
                        🔒 Результаты <span class="text-sm font-normal">(сначала пройди «Склонности»)</span>
                    </button>
                @endif

                <div class="mt-5">
                    <a href="{{ route('test.index') }}"
                       onclick="return confirm('Начать заново? Все данные текущей диагностики будут сброшены.')"
                       class="text-sm text-gray-400 hover:text-gray-600 underline transition">
                        ↩ Начать заново
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
