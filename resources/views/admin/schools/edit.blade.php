<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                    {{ $school->institution->short_name ?: $school->institution->name }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $school->name }}</h2>
            </div>
            <a href="{{ route('admin.schools.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К подразделениям
            </a>
        </div>
    </x-slot>
    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                {{ session('success') }}
            </div>
            @endif

            {{-- Статистика --}}
            <div class="bg-white shadow-sm rounded-xl p-4 flex items-center gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $school->programs_count }}</p>
                    <p class="text-xs text-gray-400">программ</p>
                </div>
                @if ($school->programs_count > 0)
                <p class="text-xs text-gray-400 flex-1">
                    Переназначение учреждения не затронет существующие программы —
                    они останутся привязаны к этому подразделению.
                </p>
                @endif
            </div>

            @include('admin.schools._form', [
                'formAction'   => route('admin.schools.update', $school),
                'school'       => $school,
                'submitLabel'  => 'Сохранить',
                'institutions' => $institutions,
            ])

        </div>
    </div>
</x-app-layout>
