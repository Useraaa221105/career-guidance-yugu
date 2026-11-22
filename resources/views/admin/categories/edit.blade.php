<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">
                    @php
                        $typeLabel = ['sphere' => 'Сфера деятельности', 'intellect' => 'Тип интеллекта', 'value' => 'Жизненная ценность'][$category->type] ?? $category->type;
                    @endphp
                    {{ $typeLabel }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $category->name }}
                </h2>
            </div>
            <a href="{{ route('admin.categories.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К категориям
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Статистика использования --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full flex items-center justify-center text-lg shrink-0"
                         style="background-color: {{ $category->color ?? '#e5e7eb' }}">
                        {{ $category->icon ?? '' }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Ответов с баллами</p>
                        <p class="text-xl font-bold text-gray-800">{{ $category->answers_count }}</p>
                    </div>
                </div>
                @if ($category->type === 'sphere')
                <div class="bg-white shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                        <span class="text-indigo-600 text-sm font-bold">П</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Программ привязано</p>
                        <p class="text-xl font-bold text-gray-800">{{ $category->programs_count }}</p>
                    </div>
                </div>
                @else
                <div class="bg-white shadow-sm rounded-xl p-4 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                        <span class="text-gray-400 text-xs font-mono">type</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Тип категории</p>
                        <p class="text-sm font-semibold text-gray-700 font-mono">{{ $category->type }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Форма редактирования --}}
            <div class="bg-white shadow-sm rounded-xl p-6 space-y-5">

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Редактируемые поля</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full font-mono">
                        {{ $category->type }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Название --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Название <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required maxlength="255"
                               value="{{ old('name', $category->name) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">
                            Отображается на странице результатов теста. Scoring работает по ID, не по названию.
                        </p>
                    </div>

                    {{-- Иконка + Цвет --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">
                                Иконка (эмодзи)
                            </label>
                            <input type="text" id="icon" name="icon" maxlength="20"
                                   value="{{ old('icon', $category->icon) }}"
                                   placeholder="💻"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition text-xl text-center">
                            @error('icon')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                                Цвет
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="colorPicker"
                                       value="{{ old('color', $category->color) ?? '#6366f1' }}"
                                       class="h-10 w-12 rounded-lg border border-gray-300 cursor-pointer p-0.5"
                                       oninput="document.getElementById('color').value = this.value; updatePreview()">
                                <input type="text" id="color" name="color" maxlength="50"
                                       value="{{ old('color', $category->color) }}"
                                       placeholder="#6366f1"
                                       class="flex-1 border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition"
                                       oninput="syncColorPicker(this.value)">
                            </div>
                            @error('color')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Предпросмотр --}}
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <span id="preview-icon"
                              class="h-10 w-10 rounded-full flex items-center justify-center text-xl shadow-sm"
                              style="background-color: {{ $category->color ?? '#e5e7eb' }}">
                            {{ $category->icon ?? '' }}
                        </span>
                        <div>
                            <p class="text-xs text-gray-400">Предпросмотр</p>
                            <p id="preview-name" class="text-sm font-semibold text-gray-800">{{ $category->name }}</p>
                        </div>
                    </div>

                    {{-- Описание --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Описание
                        </label>
                        <textarea id="description" name="description" rows="3" maxlength="1000"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                                  placeholder="Краткое описание категории...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Только для чтения: type и slug --}}
                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                        <div>
                            <p class="text-xs font-medium text-gray-400 mb-1">Тип (не изменяется)</p>
                            <p class="text-sm font-mono text-gray-600 bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5">
                                {{ $category->type }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 mb-1">Slug (не изменяется)</p>
                            <p class="text-sm font-mono text-gray-600 bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5">
                                {{ $category->slug }}
                            </p>
                        </div>
                    </div>

                    {{-- Кнопки --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            Сохранить
                        </button>
                        <a href="{{ route('admin.categories.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 transition">
                            Отмена
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        function syncColorPicker(value) {
            const picker = document.getElementById('colorPicker');
            if (/^#[0-9a-fA-F]{6}$/.test(value)) {
                picker.value = value;
            }
            updatePreview();
        }

        function updatePreview() {
            const color = document.getElementById('color').value || '#e5e7eb';
            const icon  = document.getElementById('icon').value;
            const name  = document.getElementById('name').value;
            const el    = document.getElementById('preview-icon');
            if (el) {
                el.style.backgroundColor = color;
                el.textContent = icon;
            }
            const nameEl = document.getElementById('preview-name');
            if (nameEl) nameEl.textContent = name || '…';
        }

        document.getElementById('name')?.addEventListener('input', updatePreview);
        document.getElementById('icon')?.addEventListener('input', updatePreview);
    </script>
</x-app-layout>
