<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Новое учреждение</h2>
            <a href="{{ route('admin.institutions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К учреждениям
            </a>
        </div>
    </x-slot>
    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin.institutions._form', [
                'formAction'  => route('admin.institutions.store'),
                'institution' => null,
                'submitLabel' => 'Создать учреждение',
            ])
        </div>
    </div>
</x-app-layout>
