<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Вопросы и баллы
            </h2>
            <a href="{{ route('admin.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← Панель администратора
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                {{ session('success') }}
            </div>
            @endif

            @if (session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-xl px-5 py-3">
                {{ session('warning') }}
            </div>
            @endif

            @foreach ($blocks as $block)
            <div>
                {{-- Заголовок блока --}}
                <div class="flex items-center gap-3 mb-4 flex-wrap">
                    @if($block->icon)
                    <span class="text-2xl">{{ $block->icon }}</span>
                    @endif
                    <h3 class="text-lg font-bold text-gray-800">{{ $block->name }}</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                        {{ $block->questions->count() }} вопр.
                    </span>
                    <a href="{{ route('admin.questions.create', $block) }}"
                       class="ml-auto text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-3 py-1.5 rounded-lg transition whitespace-nowrap">
                        + Добавить вопрос
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ($block->questions as $question)
                    <div class="bg-white rounded-xl shadow-sm border {{ $question->is_active ? 'border-gray-100' : 'border-amber-200 bg-amber-50' }} overflow-hidden">

                        {{-- Вопрос --}}
                        <div class="px-5 py-3 {{ $question->is_active ? 'bg-gray-50' : 'bg-amber-50' }} border-b {{ $question->is_active ? 'border-gray-100' : 'border-amber-100' }} flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <span class="text-xs font-mono text-gray-400 mt-0.5 shrink-0">
                                    #{{ $question->order_index }}
                                </span>
                                <p class="text-sm font-medium {{ $question->is_active ? 'text-gray-800' : 'text-amber-700' }}">
                                    {{ $question->text }}
                                </p>
                                @unless($question->is_active)
                                <span class="shrink-0 text-xs bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full font-medium">
                                    отключён
                                </span>
                                @endunless
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('admin.questions.edit', $question) }}"
                                   class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 px-3 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                    Редактировать
                                </a>
                                <form method="POST" action="{{ route('admin.questions.destroy', $question) }}"
                                      onsubmit="return confirm('Удалить этот вопрос и все его ответы? Это действие нельзя отменить.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Ответы --}}
                        <div class="divide-y divide-gray-50">
                            @forelse ($question->answers as $answer)
                            <div class="px-5 py-3 flex flex-col sm:flex-row sm:items-start gap-2 {{ $answer->is_active ? '' : 'opacity-60 bg-amber-50/30' }}">

                                {{-- Текст ответа --}}
                                <div class="flex-1 text-sm {{ $answer->is_active ? 'text-gray-700' : 'text-amber-700 line-through decoration-amber-300' }} flex items-center gap-2">
                                    {{ $answer->text }}
                                    @unless($answer->is_active)
                                    <span class="no-underline not-italic text-xs bg-amber-100 text-amber-600 border border-amber-200 px-1.5 py-0.5 rounded-full font-medium" style="text-decoration:none">
                                        отключён
                                    </span>
                                    @endunless
                                </div>

                                {{-- Категории и баллы + кнопка редактировать --}}
                                <div class="flex flex-wrap items-center gap-1.5 shrink-0">
                                    @forelse ($answer->categories as $category)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                                                 bg-indigo-50 text-indigo-700 border border-indigo-100 font-medium">
                                        {{ $category->name }}
                                        <span class="bg-indigo-100 text-indigo-600 rounded-full px-1.5 py-px font-bold text-xs">
                                            +{{ $category->pivot->points }}
                                        </span>
                                    </span>
                                    @empty
                                    <span class="text-xs text-gray-400 italic">без категорий</span>
                                    @endforelse

                                    <a href="{{ route('admin.answers.edit', $answer) }}"
                                       class="text-xs bg-gray-50 hover:bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap ml-1">
                                        Изменить
                                    </a>
                                    <form method="POST" action="{{ route('admin.answers.destroy', $answer) }}"
                                          onsubmit="return confirm('Удалить этот ответ? Это действие нельзя отменить.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-2.5 py-1 rounded-lg transition font-medium whitespace-nowrap">
                                            Удалить
                                        </button>
                                    </form>
                                </div>

                            </div>
                            @empty
                            <div class="px-5 py-3 text-sm text-gray-400 italic">Ответы не добавлены</div>
                            @endforelse
                        </div>

                        {{-- Добавить ответ --}}
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
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
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
