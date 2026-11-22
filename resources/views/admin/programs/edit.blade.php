<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                    {{ $program->institution->short_name ?: $program->institution->name }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Редактирование программы
                </h2>
            </div>
            <a href="{{ route('admin.programs.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К списку программ
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                {{ session('success') }}
            </div>
            @endif

            @include('admin.programs._form', [
                'formAction'      => route('admin.programs.update', $program),
                'isEdit'          => true,
                'submitLabel'     => 'Сохранить программу',
                'program'         => $program,
                'selectedCatIds'  => $selectedCatIds,
                'selectedProfIds' => $selectedProfIds,
            ])

        </div>
    </div>
</x-app-layout>
