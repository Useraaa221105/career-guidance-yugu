<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Результаты пользователей
            </h2>
            <a href="{{ route('admin.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← Панель администратора
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
            $blockLabels = [
                'sklon'     => ['label' => 'Склонности',     'color' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                'intellect' => ['label' => 'Тип интеллекта', 'color' => 'bg-violet-50 text-violet-700 border-violet-100'],
                'values'    => ['label' => 'Ценности',       'color' => 'bg-teal-50 text-teal-700 border-teal-100'],
            ];
            $educationLabels = [
                '9_классов'  => '9 классов',
                '11_классов' => '11 классов',
                'СПО'        => 'СПО',
                'высшее'     => 'Высшее',
            ];
            @endphp

            {{-- Фильтры --}}
            <div class="bg-white shadow-sm rounded-xl p-5">
                <form method="GET" action="{{ route('admin.results.index') }}" class="space-y-4">

                    {{-- Текстовый поиск --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Поиск</label>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Имя, email, город из анкеты..."
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                    </div>

                    {{-- Выборочные фильтры --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                        {{-- Образование --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Образование</label>
                            <select name="education"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все уровни</option>
                                @foreach ($educationLabels as $val => $label)
                                <option value="{{ $val }}" {{ $education === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Город --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Город</label>
                            <select name="city"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все города</option>
                                @foreach ($availableCities as $c)
                                <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Переезд --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Переезд</label>
                            <select name="relocation"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все</option>
                                <option value="yes" {{ $relocation === 'yes' ? 'selected' : '' }}>Готов к переезду</option>
                                <option value="no"  {{ $relocation === 'no'  ? 'selected' : '' }}>Не готов к переезду</option>
                            </select>
                        </div>

                        {{-- Пройденные блоки --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Пройдено блоков</label>
                            <select name="blocks"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все</option>
                                <option value="sklon"          {{ $blocks === 'sklon'          ? 'selected' : '' }}>Только склонности</option>
                                <option value="sklon_intellect" {{ $blocks === 'sklon_intellect' ? 'selected' : '' }}>Склонности + интеллект</option>
                                <option value="sklon_values"   {{ $blocks === 'sklon_values'   ? 'selected' : '' }}>Склонности + ценности</option>
                                <option value="all"            {{ $blocks === 'all'            ? 'selected' : '' }}>Все диагностические блоки</option>
                            </select>
                        </div>

                    </div>

                    {{-- Кнопки --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                            Применить
                        </button>
                        @if ($isFiltered)
                        <a href="{{ route('admin.results.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Сбросить фильтры
                        </a>
                        @endif
                        <span class="text-sm text-gray-400 ml-auto">
                            @php
                            $total = $sessions->total();
                            $word = $total === 1 ? 'результат' : ($total >= 2 && $total <= 4 ? 'результата' : 'результатов');
                            @endphp
                            {{ $total }} {{ $word }}
                        </span>
                    </div>

                </form>
            </div>

            @if ($sessions->isEmpty())

            <div class="bg-white shadow-sm rounded-xl p-12 text-center text-gray-500">
                @if ($isFiltered)
                <div class="text-4xl mb-4">🔍</div>
                <p class="text-lg font-medium text-gray-700">Ничего не найдено</p>
                <p class="mt-1 text-sm text-gray-400">Попробуйте изменить параметры поиска или
                    <a href="{{ route('admin.results.index') }}" class="text-indigo-600 hover:underline">сбросить фильтры</a>.
                </p>
                @else
                <div class="text-5xl mb-4">📋</div>
                <p class="text-lg font-medium text-gray-700">Пока нет завершённых диагностик</p>
                <p class="mt-1 text-sm text-gray-400">Здесь появятся записи, когда пользователи пройдут блок «Склонности».</p>
                @endif
            </div>

            @else

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Пользователь</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Анкета</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Пройдено блоков</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-32">Дата</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">Результат</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        @foreach ($sessions as $session)
                        @php
                            $completedSlugs = $session->sessionBlocks
                                ->whereNotNull('completed_at')
                                ->pluck('block.slug')
                                ->filter()
                                ->toArray();
                        @endphp
                        <tr class="hover:bg-gray-50 transition align-top">

                            {{-- Пользователь --}}
                            <td class="px-4 py-3">
                                @if ($session->user)
                                <div class="font-medium text-gray-800">{{ $session->user->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $session->user->email }}</div>
                                @else
                                <div class="text-gray-500 italic">Гость</div>
                                @if ($session->name)
                                <div class="text-xs text-gray-400 mt-0.5">{{ $session->name }}, {{ $session->age }} л.</div>
                                @endif
                                @endif
                            </td>

                            {{-- Анкета --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs text-gray-600">
                                    @if ($session->city)
                                    <span>{{ $session->city }}</span>
                                    @endif
                                    @if ($session->education)
                                    <span>{{ $educationLabels[$session->education] ?? $session->education }}</span>
                                    @endif
                                    @if ($session->study_form_pref)
                                    <span>{{ $session->study_form_pref }}</span>
                                    @endif
                                    @if ($session->relocation)
                                    <span class="{{ $session->relocation === 'да' ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $session->relocation === 'да' ? 'Переезд: да' : 'Переезд: нет' }}
                                    </span>
                                    @endif
                                    @if ($session->budget)
                                    <span class="{{ $session->budget === 'бюджет' ? 'text-blue-600' : 'text-orange-500' }}">
                                        {{ $session->budget === 'бюджет' ? 'Бюджет' : 'Контракт' }}
                                    </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Пройденные блоки --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($blockLabels as $slug => $info)
                                    @if (in_array($slug, $completedSlugs))
                                    <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full border
                                                 {{ $info['color'] }}">
                                        {{ $info['label'] }}
                                    </span>
                                    @endif
                                    @endforeach
                                </div>
                            </td>

                            {{-- Дата --}}
                            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                {{ $session->created_at->format('d.m.Y') }}<br>
                                <span class="text-gray-400">{{ $session->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Ссылка --}}
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('test.results', $session->token) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 text-xs font-semibold
                                          bg-indigo-50 hover:bg-indigo-100 text-indigo-700
                                          px-3 py-1.5 rounded-lg transition whitespace-nowrap">
                                    Открыть →
                                </a>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- Пагинация --}}
            @if ($sessions->hasPages())
            <div class="mt-6">
                {{ $sessions->links() }}
            </div>
            @endif

            @endif

        </div>
    </div>
</x-app-layout>
