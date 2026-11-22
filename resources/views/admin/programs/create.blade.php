<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Новая образовательная программа
            </h2>
            <a href="{{ route('admin.programs.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К списку программ
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @include('admin.programs._form', [
                'formAction'      => route('admin.programs.store'),
                'isEdit'          => false,
                'submitLabel'     => 'Создать программу',
                'program'         => null,
                'selectedCatIds'  => [],
                'selectedProfIds' => [],
            ])

        </div>
    </div>
</x-app-layout>
