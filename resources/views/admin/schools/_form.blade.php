{{-- Variables: $formAction, $school (nullable), $submitLabel, $institutions, $preselectedInstitutionId (optional) --}}

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<div class="bg-white shadow-sm rounded-xl p-6 space-y-5">
    <form method="POST" action="{{ $formAction }}">
        @csrf
        @if(isset($school)) @method('PATCH') @endif

        {{-- Учреждение --}}
        <div>
            <label for="institution_id" class="block text-sm font-medium text-gray-700 mb-1">
                Учреждение <span class="text-red-500">*</span>
            </label>
            <select id="institution_id" name="institution_id" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                <option value="">— выберите учреждение —</option>
                @foreach ($institutions as $inst)
                @php
                    $selected = old('institution_id', $school?->institution_id ?? $preselectedInstitutionId ?? null);
                @endphp
                <option value="{{ $inst->id }}" {{ $selected == $inst->id ? 'selected' : '' }}>
                    {{ $inst->short_name ?: $inst->name }} — {{ $inst->city }}
                </option>
                @endforeach
            </select>
            @error('institution_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Название --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Название подразделения <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" required maxlength="255"
                   value="{{ old('name', $school?->name) }}"
                   placeholder="Инженерная школа цифровых технологий"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Описание --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
            <textarea id="description" name="description" rows="2" maxlength="1000"
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                      placeholder="Краткое описание...">{{ old('description', $school?->description) }}</textarea>
            @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                {{ $submitLabel }}
            </button>
            <a href="{{ route('admin.schools.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition">Отмена</a>
        </div>

    </form>
</div>
