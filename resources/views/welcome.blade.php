<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карьерная траектория ЮГУ — профориентация для школьников и абитуриентов</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

{{-- ═══════════════════════════════════════════
     НАВИГАЦИЯ
═══════════════════════════════════════════ --}}
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex justify-between items-center">
        <div class="flex items-center gap-2.5">
            <img src="/images/logo-yugu.jpg" alt="ЮГУ" class="h-10 w-auto rounded">
            <span class="font-bold text-base text-gray-900 leading-tight">
                Карьерная<br class="hidden sm:block">траектория ЮГУ
            </span>
        </div>
        <div class="flex gap-2 items-center">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                    Мой кабинет
                </a>
                <a href="{{ route('test.index') }}"
                   class="text-sm font-semibold px-4 py-2 rounded-lg text-white transition"
                   style="background-color:#1B52CC;">
                    Пройти тест
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                    Войти
                </a>
                <a href="{{ route('register') }}"
                   class="text-sm font-semibold px-4 py-2 rounded-lg text-white transition"
                   style="background-color:#1B52CC;">
                    Зарегистрироваться
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════
     БЛОК 1 — HERO
═══════════════════════════════════════════ --}}
<section class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-20 pb-24 text-center">

        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-8"
             style="background-color:#1B52CC18; color:#1B52CC;">
            Бесплатно · Без регистрации · Результат сразу
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
            Найди профессию,<br>
            <span style="color:#1B52CC;">которая подходит тебе</span>
        </h1>

        <p class="text-lg sm:text-xl text-gray-500 mb-10 max-w-2xl mx-auto leading-relaxed">
            Пройди диагностику — определим твои склонности, тип мышления и ценности.
            Подберём образовательные программы и профессии в&nbsp;ЮГУ и&nbsp;его&nbsp;филиалах.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <a href="{{ route('test.index') }}"
               class="inline-flex items-center gap-2 px-8 py-4 text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all"
               style="background-color:#1B52CC;">
                Пройти тест бесплатно →
            </a>
            @auth
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-6 py-4 text-gray-700 text-base font-medium rounded-xl border border-gray-200 hover:border-gray-300 bg-white transition">
                Мои результаты
            </a>
            @endauth
        </div>

        <p class="text-sm text-gray-400 mt-4">~35 минут · 3 блока · 35 вопросов</p>

        {{-- Статистика --}}
        <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-3xl mx-auto">
            @foreach([
                ['105', 'образовательных программ'],
                ['6',   'учебных заведений ХМАО'],
                ['9',   'сфер профессиональных интересов'],
                ['3',   'блока диагностики'],
            ] as [$num, $label])
            <div class="text-center">
                <div class="text-3xl font-extrabold" style="color:#1B52CC;">{{ $num }}</div>
                <div class="text-xs text-gray-500 mt-1 leading-snug">{{ $label }}</div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     БЛОК 2 — КАК ЭТО РАБОТАЕТ
═══════════════════════════════════════════ --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Как это работает</h2>
            <p class="text-gray-500 mt-2">Три шага — от старта до готового карьерного маршрута</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach([
                [
                    'step' => '1',
                    'title' => 'Пройди диагностику',
                    'desc'  => 'Ответь на вопросы о своих интересах, типе мышления и жизненных ценностях. Честные ответы — точный результат.',
                    'color' => '#1B52CC',
                ],
                [
                    'step' => '2',
                    'title' => 'Узнай свои сильные стороны',
                    'desc'  => 'Система определит твои ключевые сферы интересов, доминирующий тип интеллекта и главные ценности.',
                    'color' => '#1DAA4F',
                ],
                [
                    'step' => '3',
                    'title' => 'Получи карьерный маршрут',
                    'desc'  => 'Подберём подходящие программы в ЮГУ, покажем конкретные профессии и объясним, почему они тебе подходят.',
                    'color' => '#1B52CC',
                ],
            ] as $item)
            <div class="bg-white rounded-2xl p-7 border border-gray-100 shadow-sm relative">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white mb-5 absolute top-7 right-7"
                     style="background-color:{{ $item['color'] }};">
                    {{ $item['step'] }}
                </div>
                <div class="mb-4 flex items-center justify-center" style="width:4rem;height:4rem;">
                    @if($item['step'] == '1')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" style="width:4rem;height:4rem;display:block;" viewBox="0 0 32 32">
                            <path d="M0 0h32v32H0z" fill="none" />
                            <path fill="#1b52cc" d="M28 28v-8.764l2.66-1.968a3 3 0 0 0 .627-4.196L29.79 11.05A3 3 0 0 0 28 9.9V9a1 1 0 0 0-.293-.707l-7-7A1 1 0 0 0 20 1H7a3 3 0 0 0-3 3v24a3 3 0 0 0 3 3h18a3 3 0 0 0 3-3M6 28V4a1 1 0 0 1 1-1h12v5.507c0 .825.669 1.493 1.493 1.493H26v.17q-.21.108-.406.253l-2.615 1.935A.5.5 0 0 0 22.5 12h-13a.5.5 0 0 0 0 1h12.611l-2.703 2H9.5a.5.5 0 0 0 0 1h8.557l-2.703 2H9.5a.5.5 0 0 0 0 1h4.503l-2.647 1.958l-.052.042H9.5a.5.5 0 0 0 0 1h1.333l-1.435 4.704a1 1 0 0 0 .956 1.292l5.472.004a1 1 0 0 0 .596-.196L26 20.716V28a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1M20.493 9A.493.493 0 0 1 20 8.507V3.414L25.586 9zm5.743 9.054l-2.681-3.634l1.17-.866l2.686 3.63zm-12.664 3.753l9.179-6.792l2.68 3.634l-9.082 6.72zm-.78.625l2.752 3.533l-.047.035l-2.614-.002l-.834-1.128z" />
                        </svg>
                    @elseif($item['step'] == '2')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" style="width:4rem;height:4rem;display:block;" viewBox="0 0 48 48">
                            <path d="M0 0h48v48H0z" fill="none" />
                            <defs>
                                <mask id="how-it-works-strong-sides-mask">
                                    <g fill="none" stroke="#fff" stroke-linejoin="round" stroke-width="4">
                                        <path fill="#555555" d="M21 38c9.389 0 17-7.611 17-17S30.389 4 21 4S4 11.611 4 21s7.611 17 17 17Z" />
                                        <path stroke-linecap="round" d="M26.657 14.343A7.98 7.98 0 0 0 21 12a7.98 7.98 0 0 0-5.657 2.343m17.879 18.879l8.485 8.485" />
                                    </g>
                                </mask>
                            </defs>
                            <path fill="#49df49" d="M0 0h48v48H0z" mask="url(#how-it-works-strong-sides-mask)" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" style="width:4rem;height:4rem;display:block;" viewBox="0 0 36 36">
                            <path d="M0 0h36v36H0z" fill="none" />
                            <path fill="#223cc2" d="M33.59 6.19A1 1 0 0 0 32.7 6l-9.61 3l-9.63-4.89a1 1 0 0 0-.84 0l-10 4.09a1 1 0 0 0-.62.93v20.48a1 1 0 0 0 1.38.92L13 26.58l9.59 4.92a1 1 0 0 0 .46.11a1 1 0 0 0 .3 0l10-3.12a1 1 0 0 0 .7-1V7a1 1 0 0 0-.46-.81M32 26.75l-8.32 2.6v-2.29h-1.6v2l-8.4-4.31v-1.69h-1.6v1.72L4 28.11V9.79l8.08-3.33v2.35h1.6V6.47l8.4 4.3v2.1h1.6V11L32 8.36Z" />
                            <path fill="#223cc2" d="M22.08 15.06h1.6v3.81h-1.6z" />
                            <path fill="#223cc2" d="M22.08 21.06h1.6v3.81h-1.6z" />
                            <path fill="#223cc2" d="M12.08 11.06h1.6v3.81h-1.6z" />
                            <path fill="#223cc2" d="M12.08 17.13h1.6v3.75h-1.6z" />
                            <path fill="none" d="M0 0h36v36H0z" />
                        </svg>
                    @endif
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     БЛОК 3 — ЧТО ТЫ ПОЛУЧИШЬ
═══════════════════════════════════════════ --}}
<section class="bg-white py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Что ты получишь на выходе</h2>
            <p class="text-gray-500 mt-2">Конкретный результат, а не общие слова</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach([
                ['🎯', 'Топ-3 сферы интересов', 'Узнаешь, к каким областям у тебя выражены склонности: IT, техника, медицина, экономика и другие.',         '#1B52CC'],
                ['🧠', 'Тип интеллекта',         'Определим доминирующий тип мышления по теории Гарднера: логический, лингвистический, пространственный и т.д.', '#6d28d9'],
                ['💎', 'Жизненные ценности',      'Выявим, что для тебя важно: карьера, творчество, помощь людям, стабильность или свобода.',                 '#0e7490'],
                ['📚', 'Подходящие программы',    'Список образовательных программ в ЮГУ, которые совпадают с твоим профилем. С описанием каждой.',            '#1DAA4F'],
                ['💼', 'Профессии и должности',   'К каждой программе — конкретные профессии, куда ты можешь прийти после окончания.',                        '#b45309'],
                ['💡', 'Объяснение результата',   'Система объяснит, почему именно эти программы тебе рекомендованы. Логика — прозрачная.',                   '#1B52CC'],
            ] as [$icon, $title, $desc, $color])
            <div class="flex gap-4 p-5 rounded-xl border border-gray-100 hover:border-gray-200 transition">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl shrink-0"
                     style="background-color:{{ $color }}15;">
                    {{ $icon }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900 text-sm">{{ $title }}</div>
                    <div class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     БЛОК 4 — ДЛЯ КОГО
═══════════════════════════════════════════ --}}
<section class="py-20" style="background-color:#f0f7ff;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Кому подходит диагностика</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach([
                [
                    'icon'  => '🏫',
                    'who'   => 'Школьникам 9–11 класса',
                    'what'  => 'Ещё не определился с профессией? Диагностика поможет понять, куда двигаться после школы — в колледж или вуз.',
                    'edu'   => 'После 9 класса → СПО · После 11 класса → бакалавриат',
                ],
                [
                    'icon'  => '🎓',
                    'who'   => 'Абитуриентам',
                    'what'  => 'Определились со сферой, но не знаете какую программу выбрать? Получите конкретный список направлений с профессиями.',
                    'edu'   => 'Бакалавриат · Специалитет · СПО',
                ],
                [
                    'icon'  => '💼',
                    'who'   => 'Студентам с дипломом',
                    'what'  => 'Хочешь продолжить образование или сменить направление? Диагностика поможет выбрать магистратуру или аспирантуру.',
                    'edu'   => 'Магистратура · Аспирантура',
                ],
            ] as $card)
            <div class="bg-white rounded-2xl p-7 border border-blue-100 shadow-sm">
                <div class="text-4xl mb-4">{{ $card['icon'] }}</div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $card['who'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $card['what'] }}</p>
                <div class="text-xs font-medium px-3 py-1.5 rounded-lg inline-block"
                     style="background-color:#1B52CC12; color:#1B52CC;">
                    {{ $card['edu'] }}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     БЛОК 5 — УЧРЕЖДЕНИЯ
═══════════════════════════════════════════ --}}
<section class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <h2 class="text-xl font-bold text-center text-gray-900 mb-8">Учебные заведения системы ЮГУ</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach([
                ['🏛️', 'Югорский государственный университет',  'Ханты-Мансийск'],
                ['🏫', 'Многопрофильный колледж',                'Ханты-Мансийск'],
                ['🔧', 'Нижневартовский нефтяной институт',      'Нижневартовск'],
                ['⚙️', 'Нефтеюганский индустриальный институт', 'Нефтеюганск'],
                ['🛢️', 'Сургутский институт нефти и технологий', 'Сургут'],
                ['🔩', 'Лянторский нефтяной техникум',           'Лянтор'],
            ] as [$icon, $name, $city])
            <div class="rounded-xl p-4 border border-gray-100 hover:border-blue-200 transition text-center">
                <div class="text-3xl mb-2">{{ $icon }}</div>
                <div class="text-xs font-semibold text-gray-800 leading-snug">{{ $name }}</div>
                <div class="text-xs text-gray-400 mt-1.5">📍 {{ $city }}</div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     БЛОК 6 — ПОВТОРНЫЙ CTA
═══════════════════════════════════════════ --}}
<section class="py-20" style="background-color:#1B52CC;">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">
            Готов узнать свой путь?
        </h2>
        <p class="text-blue-200 mb-8 text-base leading-relaxed">
            Бесплатно, без регистрации. Результат — сразу после теста.
        </p>
        <a href="{{ route('test.index') }}"
           class="inline-flex items-center gap-2 px-8 py-4 bg-white font-semibold rounded-xl text-base transition hover:bg-blue-50"
           style="color:#1B52CC;">
            Начать тест →
        </a>
        @guest
        <p class="text-blue-300 text-xs mt-5">
            Хочешь сохранить результаты?
            <a href="{{ route('register') }}" class="underline text-white">Зарегистрируйся</a>
            — это бесплатно.
        </p>
        @endguest
    </div>
</section>

{{-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ --}}
<footer class="bg-gray-900 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-white">
            <img src="/images/logo-yugu.jpg" alt="ЮГУ" class="h-8 w-auto rounded">
            <span class="text-sm font-semibold">Карьерная траектория ЮГУ</span>
        </div>
        <div class="text-xs text-gray-500 text-center">
            Югорский государственный университет · Ханты-Мансийск · ХМАО–Югра
        </div>
        <div class="flex gap-4 text-xs text-gray-400">
            <a href="{{ route('test.index') }}" class="hover:text-white transition">Пройти тест</a>
            <a href="{{ route('login') }}" class="hover:text-white transition">Войти</a>
            <a href="{{ route('register') }}" class="hover:text-white transition">Регистрация</a>
        </div>
    </div>
</footer>

</body>
</html>
