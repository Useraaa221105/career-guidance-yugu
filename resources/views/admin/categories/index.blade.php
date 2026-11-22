<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Категории
            </h2>
            <a href="{{ route('admin.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← Панель
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">

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

            @foreach ($typeLabels as $type => $label)
            @if ($groups->has($type))
            <div>
                {{-- Заголовок группы --}}
                <div class="flex items-center gap-3 mb-4">
                    <h3 class="text-base font-bold text-gray-800">{{ $label }}</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full font-mono">
                        {{ $type }}
                    </span>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                        {{ $groups[$type]->count() }} категорий
                    </span>
                </div>

                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Категория
                                </th>
                                @if ($type === 'sphere')
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">
                                    Программ
                                </th>
                                @endif
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">
                                    Ответов
                                </th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Описание
                                </th>
                                <th class="px-4 py-2.5 w-36"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($groups[$type] as $category)
                            <tr class="hover:bg-gray-50 transition">

                                {{-- Категория: цвет + иконка + название --}}
                                <td class="px-5 py-3 align-middle">
                                    <div class="flex items-center gap-3">
                                        {{-- Цветной кружок --}}
                                        <span class="shrink-0 h-7 w-7 rounded-full flex items-center justify-center text-sm shadow-sm"
                                              style="background-color: {{ $category->color ?? '#e5e7eb' }}">
                                            {{ $category->icon ?? '' }}
                                        </span>
                                        <span class="font-medium text-gray-800">{{ $category->name }}</span>
                                    </div>
                                </td>

                                {{-- Программ (только для sphere) --}}
                                @if ($type === 'sphere')
                                <td class="px-4 py-3 align-middle text-center">
                                    @if ($category->programs_count > 0)
                                    <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full
                                                 bg-indigo-100 text-indigo-700 text-xs font-bold">
                                        {{ $category->programs_count }}
                                    </span>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                @endif

                                {{-- Ответов --}}
                                <td class="px-4 py-3 align-middle text-center">
                                    @if ($category->answers_count > 0)
                                    <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full
                                                 bg-gray-100 text-gray-600 text-xs font-bold">
                                        {{ $category->answers_count }}
                                    </span>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Описание --}}
                                <td class="px-4 py-3 align-middle">
                                    @if ($category->description)
                                    <p class="text-xs text-gray-500 line-clamp-1">{{ $category->description }}</p>
                                    @else
                                    <span class="text-xs text-gray-300 italic">нет</span>
                                    @endif
                                </td>

                                {{-- Действия --}}
                                <td class="px-3 py-3 align-middle text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                            Редактировать
                                        </a>
                                        @if ($category->answers_count > 0 || $category->programs_count > 0)
                                        <span class="text-xs bg-gray-50 text-gray-400 border border-gray-200 px-2.5 py-1 rounded-lg font-medium whitespace-nowrap cursor-not-allowed"
                                              title="Нельзя удалить: категория используется в ответах теста или программах">
                                            Удалить
                                        </span>
                                        @else
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline"
                                              onsubmit="return confirm('Удалить категорию «{{ addslashes($category->name) }}»? Это действие нельзя отменить.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs bg-red-50 hover:bg-red-100 text-red-700 border border-red-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                                Удалить
                                            </button>
                                        </form>
                                        @endif
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
