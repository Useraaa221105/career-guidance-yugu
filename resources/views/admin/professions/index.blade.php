<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Справочник профессий
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.professions.create') }}"
                   class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl transition">
                    + Добавить профессию
                </a>
                <a href="{{ route('admin.index') }}"
                   class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                    ← Панель
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Счётчик --}}
            <div class="text-sm text-gray-500">
                Всего профессий: <span class="font-semibold text-gray-700">{{ $professions->total() }}</span>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Профессия
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-24 text-center">
                                Программ
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Привязана к программам
                            </th>
                            <th class="px-4 py-3 w-36"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($professions as $profession)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- Название --}}
                            <td class="px-5 py-3 align-top font-medium text-gray-800">
                                {{ $profession->name }}
                            </td>

                            {{-- Количество программ --}}
                            <td class="px-4 py-3 align-top text-center">
                                @if ($profession->programs_count > 0)
                                <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full
                                             bg-indigo-100 text-indigo-700 text-xs font-bold">
                                    {{ $profession->programs_count }}
                                </span>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Первые 3 программы --}}
                            <td class="px-4 py-3 align-top">
                                @if ($profession->programs->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($profession->programs->take(3) as $prog)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                                                 bg-gray-100 text-gray-600 border border-gray-200">
                                        @if($prog->institution)
                                        <span class="text-gray-400">{{ $prog->institution->short_name ?: Str::limit($prog->institution->name, 10) }}</span>
                                        <span class="text-gray-300">·</span>
                                        @endif
                                        {{ Str::limit($prog->name, 35) }}
                                    </span>
                                    @endforeach
                                    @if ($profession->programs_count > 3)
                                    <span class="text-xs text-gray-400 italic self-center">
                                        + ещё {{ $profession->programs_count - 3 }}
                                    </span>
                                    @endif
                                </div>
                                @else
                                <span class="text-xs text-gray-400 italic">не привязана</span>
                                @endif
                            </td>

                            {{-- Действия --}}
                            <td class="px-3 py-3 align-top text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <a href="{{ route('admin.professions.edit', $profession) }}"
                                       class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                        Редактировать
                                    </a>
                                    @if ($profession->programs_count > 0)
                                    <span class="text-xs bg-gray-50 text-gray-400 border border-gray-200 px-2.5 py-1 rounded-lg font-medium whitespace-nowrap cursor-not-allowed"
                                          title="Нельзя удалить: профессия привязана к {{ $profession->programs_count }} {{ $profession->programs_count === 1 ? 'программе' : ($profession->programs_count <= 4 ? 'программам' : 'программам') }}">
                                        Удалить
                                    </span>
                                    @else
                                    <form method="POST" action="{{ route('admin.professions.destroy', $profession) }}" class="inline"
                                          onsubmit="return confirm('Удалить профессию «{{ addslashes($profession->name) }}»? Это действие нельзя отменить.')">
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

            {{-- Пагинация --}}
            <div>
                {{ $professions->links('components.admin-pagination') }}
            </div>

        </div>
    </div>
</x-app-layout>
