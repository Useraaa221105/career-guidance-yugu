<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                    {{ $question->block->name }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Редактирование вопроса #{{ $question->order_index }}
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

            @if (session('warning'))
            <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-xl px-5 py-3">
                {{ session('warning') }}
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl p-6 space-y-6">

                <form method="POST" action="{{ route('admin.questions.update', $question) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Текст вопроса --}}
                    <div>
                        <label for="text" class="block text-sm font-medium text-gray-700 mb-1">
                            Текст вопроса
                        </label>
                        <textarea id="text" name="text" rows="3" required
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                                  placeholder="Введите текст вопроса...">{{ old('text', $question->text) }}</textarea>
                        @error('text')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Порядковый номер --}}
                    <div>
                        <label for="order_index" class="block text-sm font-medium text-gray-700 mb-1">
                            Порядок в блоке
                        </label>
                        <input type="number" id="order_index" name="order_index" min="1" max="9999" required
                               value="{{ old('order_index', $question->order_index) }}"
                               class="w-28 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                        <p class="mt-1 text-xs text-gray-400">
                            Вопросы показываются пользователю в порядке возрастания этого числа.
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
                               {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                        <div>
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Вопрос активен
                            </label>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Только активные вопросы показываются пользователям при прохождении теста.
                            </p>

                            @php
                                $activeAnswerCount = $question->answers()->where('is_active', true)->count();
                            @endphp
                            @if($activeAnswerCount < 2)
                            <p class="mt-1 text-xs text-amber-600 font-medium">
                                Внимание: у вопроса {{ $activeAnswerCount }} активных
                                {{ $activeAnswerCount === 1 ? 'ответ' : 'ответов' }}.
                                Для корректного отображения в тесте нужно минимум 2.
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Кнопки --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            Сохранить
                        </button>
                        <a href="{{ route('admin.questions.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Отмена
                        </a>
                        <form method="POST" action="{{ route('admin.questions.destroy', $question) }}"
                              class="ml-auto"
                              onsubmit="return confirm('Удалить этот вопрос и все его ответы? Это действие нельзя отменить.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-sm text-red-500 hover:text-red-700 transition font-medium">
                                Удалить вопрос
                            </button>
                        </form>
                    </div>

                </form>

            </div>

            {{-- Ответы вопроса --}}
            <div class="mt-8">
                <h3 class="text-base font-semibold text-gray-700 mb-3">Ответы к этому вопросу</h3>
                <div class="bg-white shadow-sm rounded-xl divide-y divide-gray-100">
                    @forelse($question->answers as $answer)
                    <div class="px-5 py-3 flex items-center justify-between gap-3
                                {{ $answer->is_active ? '' : 'bg-amber-50/40' }}">
                        <span class="text-sm {{ $answer->is_active ? 'text-gray-700' : 'text-amber-700 line-through decoration-amber-300' }}">
                            {{ $answer->text }}
                        </span>
                        <div class="flex items-center gap-2 shrink-0">
                            @unless($answer->is_active)
                            <span class="text-xs bg-amber-100 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full font-medium"
                                  style="text-decoration:none">
                                отключён
                            </span>
                            @endunless
                            <a href="{{ route('admin.answers.edit', $answer) }}"
                               class="text-xs bg-gray-50 hover:bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-1 rounded-lg transition font-medium">
                                Изменить
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-3 text-sm text-gray-400 italic">Ответов нет</div>
                    @endforelse

                    {{-- Добавить ответ --}}
                    <div class="px-5 py-3 bg-gray-50">
                        <form method="POST" action="{{ route('admin.answers.store', $question) }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="text" placeholder="Текст нового ответа..."
                                   class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                            <button type="submit"
                                    class="text-xs bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 px-3 py-1.5 rounded-lg transition font-medium whitespace-nowrap">
                                + Добавить ответ
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
