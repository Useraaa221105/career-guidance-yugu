<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Личный кабинет
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Приветствие --}}
            <div class="bg-white shadow-sm rounded-xl p-6">
                <p class="text-gray-700 text-lg">
                    Привет, <span class="font-semibold">{{ auth()->user()->name }}</span>!
                    Здесь хранится твоя профориентационная диагностика.
                </p>
            </div>

            {{-- Ссылка на админ-панель --}}
            @if(auth()->user()->isAdmin())
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center justify-between">
                <span class="text-amber-800 font-medium">У вас есть права администратора</span>
                <a href="{{ route('admin.index') }}"
                   class="text-sm bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                    Перейти в панель управления
                </a>
            </div>
            @endif

            @if (!$session)
                {{-- Нет диагностики — приглашение начать --}}
                <div class="bg-white shadow-sm rounded-xl p-10 text-center">
                    <div class="text-5xl mb-4">🎯</div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Диагностика ещё не пройдена</h2>
                    <p class="text-gray-500 text-sm mb-6">
                        Пройди профориентационный тест, чтобы узнать свои склонности,
                        тип интеллекта и подходящие образовательные программы.
                    </p>
                    <a href="{{ route('test.index') }}"
                       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition">
                        Начать диагностику →
                    </a>
                </div>
            @else
                @php
                    $completedSlugs = $session->sessionBlocks
                        ->whereNotNull('completed_at')
                        ->pluck('block.slug')
                        ->filter()
                        ->toArray();

                    $blockLabels = [
                        'sklon'     => ['label' => 'Склонности',     'icon' => '🎯'],
                        'intellect' => ['label' => 'Тип интеллекта', 'icon' => '🧠'],
                        'values'    => ['label' => 'Ценности',       'icon' => '💎'],
                    ];

                    $educationLabels = [
                        '9_классов'  => '9 классов',
                        '11_классов' => '11 классов',
                        'СПО'        => 'СПО',
                        'высшее'     => 'Высшее',
                    ];

                    $sklonDone = in_array('sklon', $completedSlugs);
                @endphp

                {{-- Карточка диагностики --}}
                <div class="bg-white shadow-sm rounded-xl p-6 space-y-5">

                    {{-- Мета-инфо --}}
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500">
                        <span>{{ $session->created_at->format('d.m.Y, H:i') }}</span>
                        @if ($session->city)
                            <span class="text-gray-300">·</span>
                            <span>{{ $session->city }}</span>
                        @endif
                        @if ($session->education)
                            <span class="text-gray-300">·</span>
                            <span>{{ $educationLabels[$session->education] ?? $session->education }}</span>
                        @endif
                        @if ($session->name)
                            <span class="text-gray-300">·</span>
                            <span>{{ $session->name }}</span>
                        @endif
                    </div>

                    {{-- Прогресс по блокам --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Пройденные блоки</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($blockLabels as $slug => $info)
                                @if (in_array($slug, $completedSlugs))
                                    <span class="inline-flex items-center gap-1 text-xs font-medium
                                                 bg-green-50 text-green-700 border border-green-200
                                                 px-2.5 py-1 rounded-full">
                                        {{ $info['icon'] }} {{ $info['label'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium
                                                 bg-gray-50 text-gray-400 border border-gray-200
                                                 px-2.5 py-1 rounded-full">
                                        {{ $info['icon'] }} {{ $info['label'] }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Действия --}}
                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        @if ($sklonDone)
                            <a href="{{ route('test.results', $session->token) }}"
                               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700
                                      text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition">
                                📊 Смотреть результаты
                            </a>
                        @endif

                        <a href="{{ route('test.diagnostics') }}"
                           class="inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100
                                  text-indigo-700 font-semibold text-sm px-5 py-2.5 rounded-lg transition">
                            {{ $sklonDone ? 'Управление диагностикой' : 'Продолжить диагностику' }} →
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
