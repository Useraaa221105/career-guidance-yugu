<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Новая профессия
            </h2>
            <a href="{{ route('admin.professions.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К справочнику профессий
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl p-6 space-y-5">

                <form method="POST" action="{{ route('admin.professions.store') }}">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Название профессии <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required maxlength="255" autofocus
                               value="{{ old('name') }}"
                               placeholder="Например: Инженер-программист"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">
                            После создания можно привязать профессию к образовательным программам.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            Создать профессию
                        </button>
                        <a href="{{ route('admin.professions.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Отмена
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
