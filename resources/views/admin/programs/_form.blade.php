{{--
    Shared form partial for program create/edit.
    Required variables from caller:
      $formAction     — string, form action URL
      $isEdit         — bool
      $institutions   — Collection
      $schools        — Collection
      $sphereCategories — Collection
      $professions    — Collection
      $selectedCatIds  — array of category IDs
      $selectedProfIds — array of profession IDs
    Optional:
      $program        — Program model (null on create)
--}}

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if($isEdit) @method('PATCH') @endif

    {{-- Основные поля --}}
    <div class="bg-white shadow-sm rounded-xl p-6 space-y-5">
        <h3 class="text-sm font-semibold text-gray-700 pb-2 border-b border-gray-100">
            Основные данные
        </h3>

        <div class="grid grid-cols-2 gap-4">
            {{-- Код программы --}}
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                    Код <span class="text-red-500">*</span>
                </label>
                <input type="text" id="code" name="code" required maxlength="50"
                       value="{{ old('code', $program?->code) }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition font-mono">
                @error('code')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Уровень --}}
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">
                    Уровень образования <span class="text-red-500">*</span>
                </label>
                <select id="level" name="level" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                    @foreach(['СПО', 'бакалавриат', 'специалитет', 'магистратура', 'аспирантура'] as $lvl)
                    <option value="{{ $lvl }}" {{ old('level', $program?->level) === $lvl ? 'selected' : '' }}>
                        {{ $lvl }}
                    </option>
                    @endforeach
                </select>
                @error('level')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Название --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Название программы <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" required maxlength="500"
                   value="{{ old('name', $program?->name) }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Форма обучения --}}
            <div>
                <label for="study_form" class="block text-sm font-medium text-gray-700 mb-1">
                    Форма обучения <span class="text-red-500">*</span>
                </label>
                <select id="study_form" name="study_form" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                    @foreach(['очная', 'заочная', 'очно-заочная'] as $form)
                    <option value="{{ $form }}" {{ old('study_form', $program?->study_form) === $form ? 'selected' : '' }}>
                        {{ ucfirst($form) }}
                    </option>
                    @endforeach
                </select>
                @error('study_form')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Учреждение --}}
            <div>
                <label for="institution_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Учреждение <span class="text-red-500">*</span>
                </label>
                <select id="institution_id" name="institution_id" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition"
                        onchange="filterSchools(this.value)">
                    @if(!$isEdit)
                    <option value="">— выберите учреждение —</option>
                    @endif
                    @foreach($institutions as $inst)
                    <option value="{{ $inst->id }}"
                            {{ old('institution_id', $program?->institution_id) == $inst->id ? 'selected' : '' }}>
                        {{ $inst->short_name ?: $inst->name }}
                        @if($inst->city) ({{ $inst->city }}) @endif
                    </option>
                    @endforeach
                </select>
                @error('institution_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Подразделение/школа --}}
        <div>
            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">
                Подразделение / школа
            </label>
            <select id="school_id" name="school_id"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                <option value="">— не указано —</option>
                @foreach($schools as $school)
                <option value="{{ $school->id }}"
                        data-institution="{{ $school->institution_id }}"
                        {{ old('school_id', $program?->school_id) == $school->id ? 'selected' : '' }}>
                    {{ $school->name }}
                </option>
                @endforeach
            </select>
            @error('school_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Описание --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Описание
            </label>
            <textarea id="description" name="description" rows="3" maxlength="2000"
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                      placeholder="Краткое описание программы...">{{ old('description', $program?->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Сферы деятельности --}}
    <div class="bg-white shadow-sm rounded-xl p-6 space-y-4">
        <div>
            <h3 class="text-sm font-semibold text-gray-700">
                Сферы деятельности <span class="text-red-500">*</span>
            </h3>
            <p class="text-xs text-gray-400 mt-0.5">
                Выберите хотя бы одну. Именно эти сферы используются в рекомендациях.
            </p>
            @error('categories')
            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @foreach($sphereCategories as $cat)
            @php $checked = in_array($cat->id, old('categories', $selectedCatIds)); @endphp
            <label class="flex items-center gap-2.5 cursor-pointer p-2.5 rounded-xl border transition
                          {{ $checked ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-100 hover:border-indigo-200' }}">
                <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                       {{ $checked ? 'checked' : '' }}
                       onchange="this.closest('label').className = this.checked
                           ? 'flex items-center gap-2.5 cursor-pointer p-2.5 rounded-xl border transition bg-indigo-50 border-indigo-200'
                           : 'flex items-center gap-2.5 cursor-pointer p-2.5 rounded-xl border transition bg-gray-50 border-gray-100 hover:border-indigo-200'">
                @if($cat->icon)
                <span class="text-base">{{ $cat->icon }}</span>
                @endif
                <span class="text-sm font-medium text-gray-700">{{ $cat->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Профессии --}}
    <div class="bg-white shadow-sm rounded-xl p-6 space-y-4">
        <div>
            <h3 class="text-sm font-semibold text-gray-700">Профессии</h3>
            <p class="text-xs text-gray-400 mt-0.5">
                Выбрано: <span id="profCount">{{ count(old('professions', $selectedProfIds)) }}</span>
            </p>
        </div>

        <input type="text" id="profSearch" placeholder="Фильтр профессий..."
               class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition"
               oninput="filterProfessions(this.value)">

        <div id="profList" class="max-h-64 overflow-y-auto border border-gray-100 rounded-xl divide-y divide-gray-50">
            @foreach($professions as $prof)
            @php $checked = in_array($prof->id, old('professions', $selectedProfIds)); @endphp
            <label class="flex items-center gap-3 px-4 py-2 cursor-pointer hover:bg-gray-50 transition prof-item
                          {{ $checked ? 'bg-indigo-50/50' : '' }}">
                <input type="checkbox" name="professions[]" value="{{ $prof->id }}"
                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 prof-check"
                       {{ $checked ? 'checked' : '' }}
                       onchange="updateProfCount()">
                <span class="text-sm text-gray-700 prof-name">{{ $prof->name }}</span>
            </label>
            @endforeach
        </div>

        <div class="pt-2 border-t border-gray-100">
            <label for="new_profession" class="block text-xs font-medium text-gray-600 mb-1">
                Добавить новую профессию (если нет в списке)
            </label>
            <input type="text" id="new_profession" name="new_profession" maxlength="200"
                   value="{{ old('new_profession') }}"
                   placeholder="Название профессии..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            <p class="mt-1 text-xs text-gray-400">
                Будет создана в справочнике и привязана к программе.
            </p>
        </div>
    </div>

    {{-- Кнопки --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.programs.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 transition">
            Отмена
        </a>
    </div>

</form>

<script>
    function filterSchools(institutionId) {
        const select = document.getElementById('school_id');
        Array.from(select.options).forEach(opt => {
            if (!opt.value) return;
            const matches = opt.dataset.institution === String(institutionId);
            opt.hidden = !matches;
            if (!matches && opt.selected) select.value = '';
        });
    }

    function filterProfessions(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.prof-item').forEach(item => {
            const name = item.querySelector('.prof-name').textContent.toLowerCase();
            item.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    }

    function updateProfCount() {
        document.getElementById('profCount').textContent =
            document.querySelectorAll('.prof-check:checked').length;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const instSel = document.getElementById('institution_id');
        if (instSel && instSel.value) filterSchools(instSel.value);
    });
</script>
