<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Образовательные программы
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.programs.create') }}"
                   class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl transition">
                    + Добавить программу
                </a>
                <a href="{{ route('admin.index') }}"
                   class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                    ← Панель
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
                {{ session('error') }}
            </div>
            @endif

            {{-- Фильтры --}}
            <div class="bg-white shadow-sm rounded-xl p-5">
                <form method="GET" action="{{ route('admin.programs.index') }}" class="space-y-4">
                    {{-- Текстовый поиск --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Поиск</label>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Название, код, учреждение, город..."
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                    </div>

                    {{-- Выборочные фильтры --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                        {{-- Уровень --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Уровень</label>
                            <select name="level"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все</option>
                                @foreach (['бакалавриат','специалитет','магистратура','аспирантура','СПО'] as $lvl)
                                <option value="{{ $lvl }}" {{ $levelFilter === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Форма обучения --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Форма</label>
                            <select name="study_form"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все</option>
                                @foreach (['очная' => 'Очная', 'очно-заочная' => 'Очно-заочная', 'заочная' => 'Заочная'] as $val => $label)
                                <option value="{{ $val }}" {{ $formFilter === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Учреждение --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Учреждение</label>
                            <select name="institution_id"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все</option>
                                @foreach ($filterInstitutions as $inst)
                                <option value="{{ $inst->id }}" {{ $instFilter == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->short_name ?: $inst->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Сфера --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Сфера</label>
                            <select name="category_id"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                <option value="">Все</option>
                                @foreach ($filterCategories as $cat)
                                <option value="{{ $cat->id }}" {{ $catFilter == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
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
                        <a href="{{ route('admin.programs.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Сбросить фильтры
                        </a>
                        @endif
                        <span class="text-sm text-gray-400 ml-auto">
                            {{ $totalCount }} {{ $totalCount === 1 ? 'программа' : ($totalCount >= 2 && $totalCount <= 4 ? 'программы' : 'программ') }}
                        </span>
                    </div>
                </form>
            </div>

            @php
            $levelColors = [
                'бакалавриат' => 'bg-blue-50 text-blue-700 border-blue-100',
                'специалитет' => 'bg-violet-50 text-violet-700 border-violet-100',
                'магистратура'=> 'bg-indigo-50 text-indigo-700 border-indigo-100',
                'аспирантура' => 'bg-purple-50 text-purple-700 border-purple-100',
                'СПО'         => 'bg-teal-50 text-teal-700 border-teal-100',
            ];
            $formLabels = [
                'очная'       => 'Очная',
                'очно-заочная'=> 'Очно-заочная',
                'заочная'     => 'Заочная',
            ];
            @endphp

            {{-- Пустое состояние --}}
            @if ($institutions->isEmpty())
            <div class="bg-white shadow-sm rounded-xl p-10 text-center">
                <p class="text-gray-400 text-sm italic">
                    @if ($isFiltered)
                        Ни одной программы не найдено по выбранным фильтрам.
                    @else
                        Программ пока нет.
                    @endif
                </p>
            </div>
            @endif

            @foreach ($institutions as $institution)
            @if ($institution->programs->isNotEmpty())
            <div>

                {{-- Шапка учреждения --}}
                <div class="flex items-start justify-between mb-4 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $institution->name }}
                        </h3>
                        <div class="flex items-center gap-3 mt-0.5 text-sm text-gray-500">
                            @if ($institution->city)
                            <span>{{ $institution->city }}</span>
                            @endif
                            @if ($institution->short_name && $institution->short_name !== $institution->name)
                            <span class="text-gray-300">·</span>
                            <span>{{ $institution->short_name }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="shrink-0 text-xs bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full font-medium">
                        {{ $institution->programs->count() }} программ
                    </span>
                </div>

                {{-- Таблица программ --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">Код</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Программа</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-32">Уровень</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">Форма</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Категории</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Профессии</th>
                                <th class="px-4 py-2.5 w-36"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($institution->programs as $program)
                            <tr class="hover:bg-gray-50 transition">

                                {{-- Код --}}
                                <td class="px-4 py-3 font-mono text-xs text-gray-500 align-top">
                                    {{ $program->code ?: '—' }}
                                </td>

                                {{-- Название + школа --}}
                                <td class="px-4 py-3 align-top">
                                    <div class="font-medium text-gray-800">{{ $program->name }}</div>
                                    @if ($program->school)
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $program->school->name }}</div>
                                    @endif
                                    @if ($program->description)
                                    <div class="text-xs text-gray-400 mt-0.5 italic line-clamp-1">{{ $program->description }}</div>
                                    @endif
                                </td>

                                {{-- Уровень --}}
                                <td class="px-4 py-3 align-top">
                                    @if ($program->level)
                                    <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full border
                                                 {{ $levelColors[$program->level] ?? 'bg-gray-50 text-gray-600 border-gray-100' }}">
                                        {{ $program->level }}
                                    </span>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Форма обучения --}}
                                <td class="px-4 py-3 align-top text-gray-600">
                                    {{ $formLabels[$program->study_form] ?? ($program->study_form ?: '—') }}
                                </td>

                                {{-- Категории --}}
                                <td class="px-4 py-3 align-top">
                                    @if ($program->categories->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($program->categories as $category)
                                        <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full
                                                     bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $category->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400 italic">не указаны</span>
                                    @endif
                                </td>

                                {{-- Профессии --}}
                                <td class="px-4 py-3 align-top">
                                    @if ($program->professions->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($program->professions as $profession)
                                        <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full
                                                     bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            {{ $profession->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400 italic">не указаны</span>
                                    @endif
                                </td>

                                {{-- Действия --}}
                                <td class="px-3 py-3 align-top text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.programs.edit', $program) }}"
                                           class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                            Редактировать
                                        </a>
                                        <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" class="inline"
                                              onsubmit="return confirm('Удалить программу «{{ addslashes($program->name) }}»? Это действие нельзя отменить.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs bg-red-50 hover:bg-red-100 text-red-700 border border-red-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            @endif
            @endforeach

        </div>
    </div>
</x-app-layout>
