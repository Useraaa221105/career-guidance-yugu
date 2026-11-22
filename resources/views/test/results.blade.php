<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Результаты диагностики
        </h2>
    </x-slot>

    <div class="py-8">
        {{-- Широкий контейнер --}}
        <div class="mx-auto px-4 sm:px-6 space-y-5" style="max-width:1440px;">

            {{-- ══════════════════════════════════════════════
                 БЛОК 1: Приветствие
            ══════════════════════════════════════════════ --}}
            <div class="bg-white shadow-sm sm:rounded-xl px-8 py-6 flex items-center gap-5">
                <div class="text-5xl shrink-0">🎯</div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold text-gray-900">
                        @if($session->name)Твои результаты, {{ $session->name }}!
                        @else Твои результаты готовы!
                        @endif
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">На основе ответов мы определили склонности и подобрали образовательные маршруты в ЮГУ</p>
                    @if(!in_array('intellect', $completedSlugs) || !in_array('values', $completedSlugs))
                    <div class="mt-3 inline-flex items-center gap-2 px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
                        💡 Пройди оставшиеся блоки — результаты станут точнее
                        <a href="{{ route('test.diagnostics') }}" class="font-semibold underline">вернуться</a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 БЛОК 2: Ключевые сферы
            ══════════════════════════════════════════════ --}}
            <div class="bg-white shadow-sm sm:rounded-xl p-7">
                <h2 class="text-base font-semibold text-gray-900 mb-4">🎯 Ключевые сферы интересов</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    @foreach($topCategories as $i => $category)
                    <div class="rounded-xl p-4 text-center relative" style="background-color:{{ $category->color }}18; border:2px solid {{ $category->color }}">
                        @if($i === 0)
                        <span class="absolute top-2 right-2 text-xs font-bold px-1.5 py-0.5 rounded" style="background-color:{{ $category->color }}; color:#fff;">★ #1</span>
                        @endif
                        <div class="text-3xl mb-2">{{ $category->icon }}</div>
                        <div class="font-semibold text-gray-900 text-sm">{{ $category->name }}</div>
                        <div class="text-xs text-gray-500 mt-1 leading-snug">{{ $category->description }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="space-y-2">
                    @php $maxScore = $scoreData->max('points') ?: 1; @endphp
                    @foreach($scoreData as $item)
                    @if($item['points'] > 0)
                    <div class="flex items-center gap-3">
                        <span class="text-base w-5 text-center shrink-0">{{ $item['icon'] }}</span>
                        <span class="text-xs text-gray-600 w-40 shrink-0 truncate">{{ $item['name'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-3">
                            <div class="h-3 rounded-full" style="width:{{ round($item['points'] / $maxScore * 100) }}%; background-color:{{ $item['color'] }};"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 w-7 text-right shrink-0">{{ $item['points'] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 БЛОК 3: Тип интеллекта
            ══════════════════════════════════════════════ --}}
            @if($topIntelligence)
            <div class="bg-white shadow-sm sm:rounded-xl p-7">
                <h2 class="text-base font-semibold text-gray-900 mb-4">🧠 Тип интеллекта</h2>

                <div class="flex items-center gap-4 p-4 rounded-xl mb-4"
                     style="background-color:{{ $topIntelligence->color }}12; border:2px solid {{ $topIntelligence->color }}">
                    <div class="text-4xl shrink-0">{{ $topIntelligence->icon }}</div>
                    <div>
                        <div class="font-bold text-gray-900">{{ $topIntelligence->name }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">{{ $topIntelligence->description }}</div>
                        <div class="text-xs font-semibold mt-1.5" style="color:{{ $topIntelligence->color }}">★ Доминирующий · по теории Гарднера</div>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($intelligenceData as $item)
                    @if($item['points'] > 0)
                    <div class="flex items-center gap-3">
                        <span class="text-base w-5 text-center shrink-0">{{ $item['icon'] }}</span>
                        <span class="text-xs text-gray-600 w-40 shrink-0 truncate">{{ $item['name'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full" style="width:{{ $item['percent'] }}%; background-color:{{ $item['color'] }};"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 w-7 text-right shrink-0">{{ $item['points'] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════
                 БЛОК 4: Жизненные ценности
            ══════════════════════════════════════════════ --}}
            @if($topValues->count())
            <div class="bg-white shadow-sm sm:rounded-xl p-7">
                <h2 class="text-base font-semibold text-gray-900 mb-4">💎 Жизненные ценности</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($topValues as $i => $value)
                    <div class="rounded-xl p-4 text-center relative" style="background-color:{{ $value->color }}15; border:2px solid {{ $value->color }}">
                        @if($i === 0)
                        <span class="absolute top-2 right-2 text-xs font-bold px-1.5 py-0.5 rounded" style="background-color:{{ $value->color }}; color:#fff;">★ #1</span>
                        @endif
                        <div class="text-3xl mb-2">{{ $value->icon }}</div>
                        <div class="font-semibold text-gray-900 text-sm">{{ $value->name }}</div>
                        <div class="text-xs text-gray-500 mt-1 leading-snug">{{ $value->description }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════
                 БЛОК 5: Почему эти программы?
            ══════════════════════════════════════════════ --}}
            @if($topCategories->count() && $programs->count())
            @php
                $catNames = $topCategories->pluck('name');
                $topName  = $catNames->first();

                if ($catNames->count() === 1) {
                    $spherePhrase = '«' . $catNames[0] . '»';
                } elseif ($catNames->count() === 2) {
                    $spherePhrase = '«' . $catNames[0] . '» и «' . $catNames[1] . '»';
                } else {
                    $spherePhrase = '«' . $catNames[0] . '», «' . $catNames[1] . '» и «' . $catNames[2] . '»';
                }
            @endphp
            <div class="bg-indigo-50 border border-indigo-100 sm:rounded-xl px-7 py-5">
                <h2 class="text-sm font-semibold text-indigo-900 mb-2">💡 Почему именно эти программы?</h2>
                <p class="text-sm text-indigo-800 leading-relaxed">
                    По результатам диагностики у тебя выражены интересы в сфере {{ $spherePhrase }}.
                    Ведущее направление — <strong>{{ $topName }}</strong>.
                    Программы в списке подобраны по совпадению сфер интересов, уровню образования
                    @if($session->city)и предпочтению по городу ({{ $session->city }})@endif.
                    Чем выше программа в списке — тем точнее она совпадает с твоим профилем.
                </p>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════
                 БЛОК 6: Карта карьерных путей
            ══════════════════════════════════════════════ --}}
            @if(!empty($careerMapData['programs']) && $careerMapData['programs']->isNotEmpty())
            @php
                $hasAnyNextStep = $careerMapData['programs']->contains(fn($i) => !is_null($i['next_program']));

                $nextLevelStyle = fn($level) => match($level) {
                    'бакалавриат'  => 'background-color:#dbeafe; color:#1d4ed8;',
                    'магистратура' => 'background-color:#ede9fe; color:#6d28d9;',
                    'специалитет'  => 'background-color:#dcfce7; color:#15803d;',
                    'аспирантура'  => 'background-color:#fce7f3; color:#9d174d;',
                    default        => 'background-color:#f3f4f6; color:#374151;',
                };
            @endphp

            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">

                {{-- Шапка карты --}}
                <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">🗺️ Карта карьерных путей</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Старт: <span class="font-semibold text-gray-600">🎓 {{ $careerMapData['start_label'] }}</span>
                            @if($session->city) · <span class="text-gray-500">{{ $session->city }}</span>@endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full"
                         style="background-color:#1DAA4F18; color:#1DAA4F;">
                        ⭐ #1 — лучший выбор
                    </div>
                </div>

                {{-- Граф --}}
                <div class="p-6 lg:p-10 overflow-x-auto">
                    <div id="career-graph" class="relative" style="min-width:{{ $hasAnyNextStep ? '1060px' : '800px' }};">

                        {{-- SVG-слой для кривых от старта к программам --}}
                        <svg id="career-svg"
                             class="absolute inset-0 w-full h-full pointer-events-none"
                             style="overflow:visible;">
                            <defs>
                                <marker id="arr-green" markerWidth="8" markerHeight="6"
                                        refX="7" refY="3" orient="auto">
                                    <polygon points="0 0, 8 3, 0 6" fill="#1DAA4F"/>
                                </marker>
                                <marker id="arr-gray" markerWidth="8" markerHeight="6"
                                        refX="7" refY="3" orient="auto">
                                    <polygon points="0 0, 8 3, 0 6" fill="#d1d5db"/>
                                </marker>
                            </defs>
                        </svg>

                        {{-- Колонки: [старт] [gap-svg] [траектории] --}}
                        <div class="flex items-stretch gap-0">

                            {{-- ═══ Стартовый узел ═══ --}}
                            <div class="w-40 shrink-0 flex flex-col justify-center pr-3">
                                <div id="career-start"
                                     class="rounded-xl p-5 text-center"
                                     style="background-color:#1B52CC;">
                                    <div class="text-4xl mb-2">🎓</div>
                                    <div class="text-sm font-bold leading-tight" style="color:#fff;">{{ $careerMapData['start_label'] }}</div>
                                    @if($session->name)
                                    <div class="text-xs mt-1.5 truncate" style="color:#93c5fd;">{{ $session->name }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- ═══ Зазор для SVG-кривых ═══ --}}
                            <div class="w-24 shrink-0"></div>

                            {{-- ═══ Строки траекторий ═══ --}}
                            <div class="flex-1 space-y-4">

                                @foreach($careerMapData['programs'] as $idx => $item)
                                @php
                                    $program     = $item['program'];
                                    $nextProgram = $item['next_program'];
                                    $pct         = $item['match_percent'];
                                    $isTop       = $item['is_top'];
                                    $rank        = $idx + 1;

                                    $levelStyle = $nextLevelStyle($program->level);

                                    // Профессии показываем из конечной точки цепочки
                                    $profSource = ($nextProgram && $nextProgram->professions->isNotEmpty())
                                        ? $nextProgram->professions
                                        : $program->professions;
                                @endphp

                                <div class="flex items-start gap-2">

                                    {{-- ── Основная программа ── --}}
                                    @if($isTop)
                                    <div data-program="{{ $idx }}"
                                         class="flex-1 min-w-0 rounded-xl overflow-hidden"
                                         style="border:2px solid #1DAA4F;">
                                        <div class="flex items-center justify-between px-5 py-2.5"
                                             style="background-color:#1DAA4F;">
                                            <span class="text-sm font-bold text-white">⭐ Лучший выбор · {{ $pct }}%</span>
                                            <span class="text-xs shrink-0" style="color:#d1fae5;">{{ $program->study_form }}</span>
                                        </div>
                                        <div class="px-5 py-4" style="background-color:#f0fdf4;">
                                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                                <span class="text-xs font-mono text-gray-400">{{ $program->code }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="{{ $levelStyle }}">{{ $program->level }}</span>
                                            </div>
                                            <div class="text-base font-bold text-gray-900 leading-snug">{{ $program->name }}</div>
                                            @if($program->description)
                                            <div class="text-xs text-gray-600 mt-1.5 leading-relaxed">{{ $program->description }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500 mt-1.5">
                                                {{ $program->institution->name }}
                                                @if($program->school)
                                                <span class="font-medium" style="color:#1DAA4F;"> · {{ $program->school->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @else
                                    <div data-program="{{ $idx }}"
                                         class="flex-1 min-w-0 rounded-xl border border-gray-200 bg-white px-5 py-4 hover:border-gray-300 transition-colors">
                                        <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                            <span class="text-xs font-bold text-gray-400">#{{ $rank }}</span>
                                            <span class="text-xs text-gray-400">{{ $pct }}%</span>
                                            <span class="text-xs font-mono text-gray-300">{{ $program->code }}</span>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full font-medium" style="{{ $levelStyle }}">{{ $program->level }}</span>
                                            <span class="text-xs text-gray-400">· {{ $program->study_form }}</span>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-800 leading-snug">{{ $program->name }}</div>
                                        @if($program->description)
                                        <div class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $program->description }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $program->institution->name }}
                                            @if($program->school)
                                            <span class="font-medium" style="color:#1B52CC;"> · {{ $program->school->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- ── Стрелка → следующая ступень / профессии ── --}}
                                    @if($nextProgram || $profSource->isNotEmpty())
                                    <div class="shrink-0 self-center" style="color:{{ $isTop ? '#1DAA4F' : '#d1d5db' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    @endif

                                    {{-- ── Карточка следующей ступени ── --}}
                                    @if($nextProgram)
                                    @php $nextLvlStyle = $nextLevelStyle($nextProgram->level); @endphp

                                    @if($isTop)
                                    <div class="w-52 shrink-0 rounded-xl overflow-hidden"
                                         style="border:2px solid #6366f1;">
                                        <div class="px-4 py-2 flex items-center justify-between"
                                             style="background-color:#6366f1;">
                                            <span class="text-xs font-bold text-white">Следующий шаг</span>
                                            <span class="text-xs" style="color:#c7d2fe;">{{ $nextProgram->study_form }}</span>
                                        </div>
                                        <div class="px-4 py-3" style="background-color:#eef2ff;">
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold mb-2 inline-block" style="{{ $nextLvlStyle }}">{{ $nextProgram->level }}</span>
                                            <div class="text-sm font-bold text-gray-900 leading-snug">{{ $nextProgram->name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $nextProgram->institution->name }}
                                                @if($nextProgram->school)
                                                <span style="color:#6366f1;"> · {{ $nextProgram->school->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @else
                                    <div class="w-44 shrink-0 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                                        <div class="text-xs text-gray-400 mb-1">Далее</div>
                                        <span class="text-xs px-1.5 py-0.5 rounded-full font-medium inline-block mb-1" style="{{ $nextLvlStyle }}">{{ $nextProgram->level }}</span>
                                        <div class="text-xs font-semibold text-gray-700 leading-snug">{{ $nextProgram->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $nextProgram->institution->name }}</div>
                                    </div>
                                    @endif

                                    {{-- Стрелка к профессиям --}}
                                    @if($profSource->isNotEmpty())
                                    <div class="shrink-0 self-center" style="color:{{ $isTop ? '#6366f1' : '#d1d5db' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    @endif
                                    @endif

                                    {{-- ── Профессии ── --}}
                                    @if($profSource->isNotEmpty())
                                    <div class="w-48 shrink-0 flex flex-wrap gap-1.5 content-start pt-0.5">
                                        @foreach($profSource as $prof)
                                        <span class="text-xs px-2 py-1 rounded-lg font-medium"
                                              @if($isTop)
                                                  style="background-color:#dcfce7; color:#166534;"
                                              @else
                                                  style="background-color:#f3f4f6; color:#6b7280;"
                                              @endif>
                                            {{ $prof->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @else
                                    {{-- Резервируем место, только если нет ни next, ни professions --}}
                                    @if(!$nextProgram)
                                    <div class="w-48 shrink-0"></div>
                                    @endif
                                    @endif

                                </div>{{-- /row --}}
                                @endforeach

                            </div>{{-- /trajectories --}}
                        </div>{{-- /main flex --}}
                    </div>{{-- /career-graph --}}
                </div>

                {{-- Легенда --}}
                <div class="px-8 pb-5 flex items-center gap-6 flex-wrap">
                    <p class="text-xs text-gray-400">Процент совпадения рассчитан по пересечению сфер интересов и набранным баллам · программы ЮГУ</p>
                    @if($hasAnyNextStep)
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span class="inline-flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#1DAA4F;"></span> Сейчас
                        </span>
                        <span>→</span>
                        <span class="inline-flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#6366f1;"></span> Следующий шаг
                        </span>
                        <span>→</span>
                        <span>Профессии</span>
                    </div>
                    @endif
                </div>

            </div>

            {{-- JS: SVG bezier curves от старта к основным программам --}}
            @push('scripts')
            <script>
            (function () {
                function draw() {
                    var svg       = document.getElementById('career-svg');
                    var container = document.getElementById('career-graph');
                    var startEl   = document.getElementById('career-start');
                    var nodes     = container ? container.querySelectorAll('[data-program]') : [];
                    if (!svg || !container || !startEl || !nodes.length) return;

                    Array.from(svg.children).forEach(function(c) {
                        if (c.tagName !== 'defs') svg.removeChild(c);
                    });

                    var cr = container.getBoundingClientRect();
                    var sr = startEl.getBoundingClientRect();
                    var fx = sr.right - cr.left;
                    var fy = sr.top + sr.height / 2 - cr.top;

                    nodes.forEach(function(node) {
                        var idx   = parseInt(node.getAttribute('data-program'), 10);
                        var isTop = idx === 0;
                        var nr    = node.getBoundingClientRect();
                        var tx    = nr.left - cr.left;
                        var ty    = nr.top + nr.height / 2 - cr.top;
                        var mx    = fx + (tx - fx) * 0.5;

                        var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('d',
                            'M ' + fx + ' ' + fy +
                            ' C ' + mx + ' ' + fy +
                            ', '  + mx + ' ' + ty +
                            ', '  + tx + ' ' + ty
                        );
                        path.setAttribute('fill', 'none');
                        path.setAttribute('stroke',       isTop ? '#1DAA4F' : '#e5e7eb');
                        path.setAttribute('stroke-width', isTop ? '2.5'     : '1.5');
                        path.setAttribute('marker-end',   isTop ? 'url(#arr-green)' : 'url(#arr-gray)');
                        if (!isTop) path.setAttribute('stroke-dasharray', '5 4');
                        svg.appendChild(path);
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', draw);
                } else {
                    draw();
                }
                window.addEventListener('resize', draw);
            })();
            </script>
            @endpush

            @endif

            {{-- ══════════════════════════════════════════════
                 Кнопки
            ══════════════════════════════════════════════ --}}
            <div class="flex gap-3 justify-center pb-6">
                <a href="{{ route('test.diagnostics') }}"
                   class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    ← К диагностике
                </a>
                <button onclick="window.print()"
                   class="inline-flex items-center px-6 py-2.5 text-white rounded-lg text-sm font-medium hover:opacity-90 transition"
                   style="background-color:#1B52CC;">
                    🖨 Распечатать
                </button>
            </div>

        </div>
    </div>

</x-app-layout>
