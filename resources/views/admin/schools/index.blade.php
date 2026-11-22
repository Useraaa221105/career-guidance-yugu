<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Школы / подразделения</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.schools.create') }}"
                   class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl transition">
                    + Добавить подразделение
                </a>
                <a href="{{ route('admin.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">← Панель</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Подразделение</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Учреждение</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">Программ</th>
                            <th class="px-4 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($schools as $school)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 align-middle font-medium text-gray-800">
                                {{ $school->name }}
                            </td>
                            <td class="px-4 py-3 align-middle text-gray-600">
                                {{ $school->institution->short_name ?: $school->institution->name }}
                            </td>
                            <td class="px-4 py-3 align-middle text-center">
                                @if ($school->programs_count)
                                <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                                    {{ $school->programs_count }}
                                </span>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 align-middle text-right">
                                <a href="{{ route('admin.schools.edit', $school) }}"
                                   class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                    Редактировать
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400 italic">Подразделений пока нет</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
