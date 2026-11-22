<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Профориентационная диагностика ЮГУ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <div class="text-center mb-8">
                    <div class="text-5xl mb-4">🎓</div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Анкета участника</h1>
                    <p class="text-gray-500">Расскажи о себе — это поможет подобрать подходящие программы обучения</p>
                </div>

                <form method="POST" action="{{ route('test.start') }}" class="space-y-7">
                    @csrf

                    {{-- Имя, возраст, пол --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <x-input-label for="name" value="Твоё имя" />
                            <x-text-input id="name" name="name" type="text" placeholder="Например, Иван" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="age" value="Возраст" />
                            <x-text-input id="age" name="age" type="number" min="10" max="99" placeholder="18" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Пол" />
                        <div class="mt-2 grid grid-cols-3 gap-2" id="gender_group">
                            @foreach(['мужской' => 'Мужской', 'женский' => 'Женский', 'не_указывать' => 'Не указывать'] as $value => $label)
                            <label class="radio-label flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer text-center hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="gender" value="{{ $value }}" class="sr-only">
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Тип учебного заведения --}}
                    <div>
                        <x-input-label value="Где ты сейчас учишься?" />
                        <div class="mt-2 space-y-2" id="institution_type_group">
                            @foreach([
                                'школа'        => 'В общеобразовательной школе / гимназии / лицее',
                                'колледж'       => 'В техникуме / колледже',
                                'университет'   => 'В университете / институте / вузе',
                                'не_обучаюсь'   => 'Не обучаюсь в учебном заведении',
                            ] as $value => $label)
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="institution_type" value="{{ $value }}" class="text-indigo-600">
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Город --}}
                    <div>
                        <x-input-label for="city_select" value="Твой город" />
                        <select id="city_select" name="city_select"
                            onchange="handleCityChange(this)"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">— Выберите город —</option>
                            <option value="Ханты-Мансийск">Ханты-Мансийск</option>
                            <option value="Лянтор">Лянтор</option>
                            <option value="Нефтеюганск">Нефтеюганск</option>
                            <option value="Нижневартовск">Нижневартовск</option>
                            <option value="Сургут">Сургут</option>
                            <option value="Другой">Другой город</option>
                        </select>
                        <input type="text" id="city_custom" name="city_custom"
                            placeholder="Введите название города"
                            class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm hidden">
                        <input type="hidden" id="city" name="city">
                        <p class="text-xs text-gray-400 mt-1">Учтём учреждения рядом с тобой</p>
                    </div>

                    {{-- Готов переехать? --}}
                    <div>
                        <x-input-label value="Готов переехать в другой город для обучения?" />
                        <div class="mt-2 space-y-2" id="relocation_group">
                            @foreach(['да' => 'Однозначно да', 'возможно' => 'Возможно, если потребуется', 'нет' => 'Однозначно нет'] as $value => $label)
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="relocation" value="{{ $value }}" class="text-indigo-600">
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Нужно общежитие? --}}
                    <div>
                        <x-input-label value="Потребуется ли вам общежитие?" />
                        <div class="mt-2 grid grid-cols-3 gap-2" id="dormitory_group">
                            @foreach(['да' => 'Да', 'возможно' => 'Возможно', 'нет' => 'Нет'] as $value => $label)
                            <label class="radio-label flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer text-center hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="dormitory" value="{{ $value }}" class="sr-only">
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Образование --}}
                    <div>
                        <x-input-label value="Текущий уровень образования" />
                        <div class="mt-2 space-y-2" id="education_group">
                            @foreach([
                                '9_классов'  => '9 классов (неполное среднее)',
                                '11_классов' => '11 классов (среднее общее)',
                                'СПО'        => 'Среднее профессиональное (колледж/техникум)',
                                'высшее'     => 'Высшее образование',
                            ] as $value => $label)
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="education" value="{{ $value }}" class="text-indigo-600" required>
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('education')" class="mt-1" />
                    </div>

                    {{-- ЕГЭ-статус --}}
                    <div>
                        <x-input-label value="Насколько определился с предметами ЕГЭ?" />
                        <div class="mt-2 space-y-2" id="ege_group">
                            @foreach([
                                'определился'     => 'Полностью определился — точно знаю предметы',
                                'выбираю'          => 'Выбираю между несколькими вариантами',
                                'примерно'         => 'Не определился, но понимаю предпочтительные',
                                'не_определился'   => 'Совсем не определился',
                                'уже_сдавал'       => 'Уже сдавал ЕГЭ по некоторым или всем предметам',
                            ] as $value => $label)
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="ege_status" value="{{ $value }}" class="text-indigo-600">
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Темп --}}
                    <div>
                        <x-input-label value="Когда хочешь начать работать по специальности?" />
                        <div class="mt-2 space-y-2" id="pace_group">
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="study_pace" value="быстро" class="text-indigo-600" required>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Как можно быстрее (1–3 года)</span>
                                    <p class="text-xs text-gray-400">Предложим СПО и программы покороче</p>
                                </div>
                            </label>
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="study_pace" value="не_тороплюсь" class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Не тороплюсь, хочу хорошее образование</span>
                                    <p class="text-xs text-gray-400">Предложим бакалавриат, специалитет и магистратуру</p>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('study_pace')" class="mt-1" />
                    </div>

                    {{-- Форма обучения --}}
                    <div>
                        <x-input-label value="Предпочитаемый формат обучения" />
                        <div class="mt-2 space-y-2" id="form_group">
                            @foreach([
                                'очная'        => ['Очное обучение', 'С посещением учебного заведения на постоянной основе'],
                                'заочная'      => ['Заочное обучение', 'Посещение учебного заведения только в сессионную неделю'],
                                'очно-заочная' => ['Комбинированное (очно-заочное)', 'Дистанционное в сочетании с очным'],
                                'любая'        => ['Любой формат', 'Мне всё равно'],
                            ] as $value => [$label, $hint])
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="study_form_pref" value="{{ $value }}" class="text-indigo-600" required>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                    <p class="text-xs text-gray-400">{{ $hint }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('study_form_pref')" class="mt-1" />
                    </div>

                    {{-- Бюджет --}}
                    <div>
                        <x-input-label value="Планируемый бюджет на образование" />
                        <div class="mt-2 space-y-2" id="budget_group">
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="budget" value="бюджет" class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Готов учиться только бесплатно (на бюджете)</span>
                                </div>
                            </label>
                            <label class="radio-label flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-all">
                                <input type="radio" name="budget" value="платно" class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Готов учиться платно</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 text-base">
                            Начать диагностику →
                        </x-primary-button>
                        <p class="text-center text-xs text-gray-400 mt-2">4 блока · ~35 минут · Без регистрации</p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.radio-label input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                var groupName = this.name;
                document.querySelectorAll('input[name="' + groupName + '"]').forEach(function(r) {
                    r.closest('.radio-label').classList.remove('border-indigo-500', 'bg-indigo-50');
                    r.closest('.radio-label').classList.add('border-gray-200');
                });
                this.closest('.radio-label').classList.add('border-indigo-500', 'bg-indigo-50');
                this.closest('.radio-label').classList.remove('border-gray-200');
            });
        });

        function handleCityChange(select) {
            var customInput = document.getElementById('city_custom');
            var cityHidden  = document.getElementById('city');
            if (select.value === 'Другой') {
                customInput.classList.remove('hidden');
                customInput.required = true;
                customInput.focus();
                cityHidden.value = '';
                customInput.oninput = function() { cityHidden.value = this.value; };
            } else {
                customInput.classList.add('hidden');
                customInput.required = false;
                customInput.value = '';
                cityHidden.value = select.value;
            }
        }

        document.querySelector('form').addEventListener('submit', function() {
            var select = document.getElementById('city_select');
            var cityHidden = document.getElementById('city');
            var customInput = document.getElementById('city_custom');
            if (select.value === 'Другой') {
                cityHidden.value = customInput.value;
            } else {
                cityHidden.value = select.value;
            }
        });
    </script>
</x-app-layout>
