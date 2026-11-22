<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ $block->name }}</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Вопрос {{ $number }} из {{ $total }}
                </h2>
            </div>
            <span class="text-sm text-gray-500">{{ $progress }}% пройдено</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Прогресс-бар --}}
            <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                     style="width: {{ $progress }}%"></div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ $question->text }}</h2>

                <form method="POST" action="{{ route('test.answer', [$block->slug, $number]) }}" id="answerForm">
                    @csrf
                    <input type="hidden" name="answer_id" id="selectedAnswer">

                    <div class="space-y-3" id="answersList">
                        @foreach($activeAnswers as $answer)
                        <button type="button"
                            onclick="selectAnswer({{ $answer->id }}, this)"
                            class="w-full text-left p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-150 text-sm font-medium text-gray-700 answer-btn">
                            {{ $answer->text }}
                        </button>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        @if($number > 1)
                        <a href="{{ route('test.question', [$block->slug, $number - 1]) }}"
                           class="text-sm text-gray-500 hover:text-gray-700">
                            ← Назад
                        </a>
                        @else
                        <a href="{{ route('test.diagnostics') }}"
                           class="text-sm text-gray-500 hover:text-gray-700">
                            ← К списку блоков
                        </a>
                        @endif

                        <x-primary-button type="submit" id="submitBtn" disabled
                            class="opacity-50 cursor-not-allowed" onclick="return checkSelected()">
                            {{ $number < $total ? 'Следующий вопрос →' : 'Завершить блок ✓' }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function selectAnswer(id, btn) {
            document.querySelectorAll('.answer-btn').forEach(b => {
                b.classList.remove('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
                b.classList.add('border-gray-200');
            });
            btn.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
            btn.classList.remove('border-gray-200');
            document.getElementById('selectedAnswer').value = id;

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function checkSelected() {
            return document.getElementById('selectedAnswer').value !== '';
        }
    </script>
</x-app-layout>
