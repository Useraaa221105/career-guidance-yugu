<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Учреждения</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $institution->name }}</h2>
            </div>
            <a href="{{ route('admin.institutions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К учреждениям
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
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white shadow-sm rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $institution->programs_count }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">образовательных программ</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $institution->schools_count }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">школ / подразделений</p>
                </div>
            </div>

            @include('admin.institutions._form', [
                'formAction'  => route('admin.institutions.update', $institution),
                'institution' => $institution,
                'submitLabel' => 'Сохранить',
            ])

            @if ($institution->schools_count > 0)
            <div class="bg-white shadow-sm rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Подразделения</h3>
                <div class="space-y-1">
                    @foreach ($institution->schools as $school)
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-sm text-gray-700">{{ $school->name }}</span>
                        <a href="{{ route('admin.schools.edit', $school) }}"
                           class="text-xs text-indigo-600 hover:text-indigo-800 transition">Редактировать</a>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.schools.create') }}?institution_id={{ $institution->id }}"
                   class="mt-3 inline-block text-xs text-indigo-600 hover:text-indigo-800 transition">
                    + Добавить подразделение
                </a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
