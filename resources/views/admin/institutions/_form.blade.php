{{-- Shared form for institution create/edit --}}
{{-- Variables: $formAction, $institution (nullable), $submitLabel --}}

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
        @if(isset($institution)) @method('PATCH') @endif

        {{-- Название --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Полное название <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" required maxlength="255"
                   value="{{ old('name', $institution?->name) }}"
                   placeholder="Югорский государственный университет"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Краткое название --}}
            <div>
                <label for="short_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Краткое название / аббревиатура
                </label>
                <input type="text" id="short_name" name="short_name" maxlength="100"
                       value="{{ old('short_name', $institution?->short_name) }}"
                       placeholder="ЮГУ"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                @error('short_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Город --}}
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                    Город <span class="text-red-500">*</span>
                </label>
                <input type="text" id="city" name="city" required maxlength="100"
                       value="{{ old('city', $institution?->city) }}"
                       placeholder="Ханты-Мансийск"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Тип --}}
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                Тип учреждения <span class="text-red-500">*</span>
            </label>
            <select id="type" name="type" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                @foreach(['университет', 'институт', 'филиал', 'колледж', 'техникум'] as $t)
                <option value="{{ $t }}" {{ old('type', $institution?->type) === $t ? 'selected' : '' }}>
                    {{ ucfirst($t) }}
                </option>
                @endforeach
            </select>
            @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Описание --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Описание
            </label>
            <textarea id="description" name="description" rows="3" maxlength="1000"
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition resize-y"
                      placeholder="Краткая информация об учреждении...">{{ old('description', $institution?->description) }}</textarea>
            @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Кнопки --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                {{ $submitLabel }}
            </button>
            <a href="{{ route('admin.institutions.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition">Отмена</a>
        </div>

    </form>
</div>
