<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                    @if($block->icon) {{ $block->icon }} @endif {{ $block->name }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Новый вопрос
                </h2>
            </div>
            <a href="{{ route('admin.questions.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К списку вопросов
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl p-6 space-y-5">

                <form method="POST" action="{{ route('admin.questions.store', $block) }}">
                    @csrf

                    {{-- Текст вопроса --}}
                    <div>
                        <label for="text" class="block text-sm font-medium text-gray-700 mb-1">
                            Текст вопроса <span class="text-red-500">*</span>
                        </label>
                        <textarea id="text" name="text" rows="3" required autofocus
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                                  placeholder="Введите текст вопроса...">{{ old('text') }}</textarea>
                        @error('text')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Порядковый номер --}}
                    <div>
                        <label for="order_index" class="block text-sm font-medium text-gray-700 mb-1">
                            Порядок в блоке <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="order_index" name="order_index" min="1" max="9999" required
                               value="{{ old('order_index', $nextOrder) }}"
                               class="w-28 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                        <p class="mt-1 text-xs text-gray-400">
                            Сейчас в блоке {{ $block->questions()->count() }} вопр. Следующий свободный номер: {{ $nextOrder }}.
                        </p>
                        @error('order_index')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Активность --}}
                    <div class="flex items-start gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ old('is_active') ? 'checked' : '' }}>
                        <div>
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Сразу активировать
                            </label>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Рекомендуется оставить выключенным: сначала добавьте варианты ответов и назначьте им баллы.
                                Вопрос без активных ответов всё равно не будет показан в тесте.
                            </p>
                        </div>
                    </div>

                    {{-- Кнопки --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            Создать вопрос
                        </button>
                        <a href="{{ route('admin.questions.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Отмена
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
