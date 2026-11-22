<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                    {{ $block->name }} / Вопрос #{{ $answer->question->order_index }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Редактирование ответа
                </h2>
            </div>
            <a href="{{ route('admin.questions.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К списку вопросов
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Контекст: вопрос --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-5 py-3">
                <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide mb-1">Вопрос</p>
                <p class="text-sm text-indigo-800 font-medium">{{ $answer->question->text }}</p>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-6">
                <form method="POST" action="{{ route('admin.answers.update', $answer) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Текст ответа --}}
                    <div class="mb-5">
                        <label for="text" class="block text-sm font-medium text-gray-700 mb-1">
                            Текст ответа
                        </label>
                        <textarea id="text" name="text" rows="2" required
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                                  placeholder="Введите текст ответа...">{{ old('text', $answer->text) }}</textarea>
                        @error('text')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Активность --}}
                    <div class="flex items-start gap-3 mb-6 pb-6 border-b border-gray-100">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               {{ old('is_active', $answer->is_active) ? 'checked' : '' }}>
                        <div>
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Ответ активен
                            </label>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Только активные ответы показываются пользователям при прохождении теста.
                            </p>
                        </div>
                    </div>

                    {{-- Баллы по категориям --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Баллы по категориям</h3>
                        <p class="text-xs text-gray-400 mb-4">
                            Укажите, сколько баллов (0–5) даёт этот ответ в каждую категорию.
                            Если 0 или пусто — связь удаляется. Активный ответ должен иметь хотя бы одну категорию с баллами &gt; 0.
                        </p>

                        @error('points')
                        <p class="mb-3 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror

                        <div class="space-y-2">
                            @foreach($categories as $category)
                            @php
                                $currentPoints = old("points.{$category->id}", $existingPoints->get($category->id, 0));
                            @endphp
                            <div class="flex items-center gap-3 py-2 px-3 rounded-lg {{ $currentPoints > 0 ? 'bg-indigo-50 border border-indigo-100' : 'bg-gray-50 border border-transparent' }} transition-colors">
                                <div class="flex-1 flex items-center gap-2">
                                    @if($category->icon)
                                    <span class="text-base">{{ $category->icon }}</span>
                                    @endif
                                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                    @if($category->type !== 'sphere')
                                    <span class="text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded-full">
                                        {{ $category->type }}
                                    </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <input type="number"
                                           name="points[{{ $category->id }}]"
                                           value="{{ $currentPoints ?: '' }}"
                                           min="0" max="5" step="1"
                                           placeholder="0"
                                           class="w-16 text-center border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                                    <span class="text-xs text-gray-400">баллов</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Кнопки --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            Сохранить
                        </button>
                        <a href="{{ route('admin.questions.edit', $answer->question) }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            ← К вопросу
                        </a>
                        <a href="{{ route('admin.questions.index') }}"
                           class="text-sm text-gray-400 hover:text-gray-600 transition">
                            К списку
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
