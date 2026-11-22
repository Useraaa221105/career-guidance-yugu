<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Учреждения</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.institutions.create') }}"
                   class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl transition">
                    + Добавить учреждение
                </a>
                <a href="{{ route('admin.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">← Панель</a>
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

            @php
            $typeColors = [
                'университет' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                'институт'    => 'bg-blue-50 text-blue-700 border-blue-100',
                'филиал'      => 'bg-violet-50 text-violet-700 border-violet-100',
                'колледж'     => 'bg-teal-50 text-teal-700 border-teal-100',
                'техникум'    => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            ];
            @endphp

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Учреждение</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-32">Тип</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-32">Город</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">Программ</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">Школ</th>
                            <th class="px-4 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($institutions as $inst)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 align-middle">
                                <div class="font-medium text-gray-800">{{ $inst->name }}</div>
                                @if ($inst->short_name)
                                <div class="text-xs text-gray-400 mt-0.5">{{ $inst->short_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <span class="inline-flex text-xs font-medium px-2 py-0.5 rounded-full border
                                             {{ $typeColors[$inst->type] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ $inst->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-middle text-gray-600">{{ $inst->city ?: '—' }}</td>
                            <td class="px-4 py-3 align-middle text-center">
                                @if ($inst->programs_count)
                                <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                                    {{ $inst->programs_count }}
                                </span>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-middle text-center">
                                @if ($inst->schools_count)
                                <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-bold">
                                    {{ $inst->schools_count }}
                                </span>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 align-middle text-right">
                                <a href="{{ route('admin.institutions.edit', $inst) }}"
                                   class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                    Редактировать
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-center text-sm text-gray-400 italic">Учреждений пока нет</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
