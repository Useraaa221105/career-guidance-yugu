<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Новое подразделение</h2>
            <a href="{{ route('admin.schools.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К подразделениям
            </a>
        </div>
    </x-slot>
    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin.schools._form', [
                'formAction'               => route('admin.schools.store'),
                'school'                   => null,
                'submitLabel'              => 'Создать подразделение',
                'institutions'             => $institutions,
                'preselectedInstitutionId' => request('institution_id'),
            ])
        </div>
    </div>
</x-app-layout>
