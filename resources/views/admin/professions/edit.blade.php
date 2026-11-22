<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Справочник профессий</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Редактирование профессии
                </h2>
            </div>
            <a href="{{ route('admin.professions.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                ← К справочнику
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

            <form method="POST" action="{{ route('admin.professions.update', $profession) }}">
                @csrf
                @method('PATCH')

                {{-- Название --}}
                <div class="bg-white shadow-sm rounded-xl p-6 space-y-5">
                    <h3 class="text-sm font-semibold text-gray-700 pb-2 border-b border-gray-100">
                        Название
                    </h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Название профессии <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required maxlength="255"
                               value="{{ old('name', $profession->name) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">
                            Изменение названия сразу отразится в карьерной карте и страницах результатов.
                        </p>
                    </div>
                </div>

                {{-- Привязанные программы --}}
                <div class="bg-white shadow-sm rounded-xl p-6 space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Привязанные программы</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Выбрано: <span id="progCount">{{ count(old('programs', $selectedProgramIds)) }}</span>
                        </p>
                    </div>

                    {{-- Поиск --}}
                    <input type="text" id="progSearch" placeholder="Поиск по названию программы..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition"
                           oninput="filterPrograms(this.value)">

                    {{-- Программы сгруппированы по учреждению --}}
                    <div id="progList" class="max-h-72 overflow-y-auto border border-gray-100 rounded-xl">
                        @foreach ($institutions as $institution)
                        @if ($institution->programs->isNotEmpty())
                        <div class="prog-institution-group">
                            {{-- Заголовок учреждения --}}
                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 sticky top-0 z-10">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    {{ $institution->short_name ?: $institution->name }}
                                </span>
                            </div>
                            {{-- Программы --}}
                            <div class="divide-y divide-gray-50">
                                @foreach ($institution->programs as $prog)
                                @php $checked = in_array($prog->id, old('programs', $selectedProgramIds)); @endphp
                                <label class="flex items-start gap-3 px-4 py-2.5 cursor-pointer hover:bg-gray-50 transition prog-item
                                              {{ $checked ? 'bg-indigo-50/40' : '' }}">
                                    <input type="checkbox" name="programs[]" value="{{ $prog->id }}"
                                           class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 prog-check"
                                           {{ $checked ? 'checked' : '' }}
                                           onchange="updateProgCount()">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm text-gray-700 prog-name">{{ $prog->name }}</span>
                                        <span class="ml-2 text-xs text-gray-400">{{ $prog->level }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-400">
                        Изменение привязок сразу влияет на карьерную карту в результатах теста.
                    </p>
                </div>

                {{-- Кнопки --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                        Сохранить
                    </button>
                    <a href="{{ route('admin.professions.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700 transition">
                        Отмена
                    </a>
                </div>

            </form>

        </div>
    </div>

    <script>
        function filterPrograms(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.prog-institution-group').forEach(group => {
                let visibleCount = 0;
                group.querySelectorAll('.prog-item').forEach(item => {
                    const name = item.querySelector('.prog-name').textContent.toLowerCase();
                    const show = !q || name.includes(q);
                    item.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });
                group.style.display = visibleCount > 0 ? '' : 'none';
            });
        }

        function updateProgCount() {
            document.getElementById('progCount').textContent =
                document.querySelectorAll('.prog-check:checked').length;
        }
    </script>
</x-app-layout>
