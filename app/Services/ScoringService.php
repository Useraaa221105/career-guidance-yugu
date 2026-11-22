<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Program;
use App\Models\TestSession;
use Illuminate\Support\Collection;

class ScoringService
{
    /**
     * Подсчитать баллы по сферам (type='sphere') на основе ответов сессии.
     */
    public function calculateScores(TestSession $session): Collection
    {
        $sphereIds = Category::where('type', 'sphere')->pluck('id');
        $scores = $sphereIds->mapWithKeys(fn($id) => [$id => 0]);

        foreach ($session->sessionAnswers as $sessionAnswer) {
            foreach ($sessionAnswer->answer->categories as $category) {
                if (isset($scores[$category->id])) {
                    $scores[$category->id] += $category->pivot->points;
                }
            }
        }

        return $scores->sortDesc();
    }

    /**
     * Подсчитать баллы по типам интеллекта (type='intellect').
     */
    public function calculateIntelligenceScores(TestSession $session): Collection
    {
        $intellectIds = Category::where('type', 'intellect')->pluck('id');
        $scores = $intellectIds->mapWithKeys(fn($id) => [$id => 0]);

        foreach ($session->sessionAnswers as $sessionAnswer) {
            foreach ($sessionAnswer->answer->categories as $category) {
                if (isset($scores[$category->id])) {
                    $scores[$category->id] += $category->pivot->points;
                }
            }
        }

        return $scores->sortDesc();
    }

    /**
     * Подсчитать баллы по жизненным ценностям (type='value').
     */
    public function calculateValueScores(TestSession $session): Collection
    {
        $valueIds = Category::where('type', 'value')->pluck('id');
        $scores = $valueIds->mapWithKeys(fn($id) => [$id => 0]);

        foreach ($session->sessionAnswers as $sessionAnswer) {
            foreach ($sessionAnswer->answer->categories as $category) {
                if (isset($scores[$category->id])) {
                    $scores[$category->id] += $category->pivot->points;
                }
            }
        }

        return $scores->sortDesc();
    }

    /**
     * Получить топ N сфер по баллам.
     */
    public function getTopCategories(Collection $scores, int $top = 3): Collection
    {
        $topIds = $scores->take($top)->keys();
        return Category::whereIn('id', $topIds)
            ->get()
            ->sortBy(fn($cat) => array_search($cat->id, $topIds->toArray()))
            ->values();
    }

    /**
     * Получить доминирующий тип интеллекта.
     */
    public function getTopIntelligenceType(Collection $scores): ?Category
    {
        $topId = $scores->keys()->first();
        return $topId ? Category::find($topId) : null;
    }

    /**
     * Получить топ N жизненных ценностей.
     */
    public function getTopValues(Collection $scores, int $top = 3): Collection
    {
        $topIds = $scores->take($top)->keys();
        return Category::whereIn('id', $topIds)
            ->get()
            ->sortBy(fn($cat) => array_search($cat->id, $topIds->toArray()))
            ->values();
    }

    /**
     * Карта переходов между уровнями образования.
     */
    private const NEXT_LEVELS = [
        'СПО'          => ['бакалавриат', 'специалитет'],
        'бакалавриат'  => ['магистратура'],
        'специалитет'  => ['магистратура'],
        'магистратура' => ['аспирантура'],
        'аспирантура'  => [],
    ];

    /**
     * Найти наиболее релевантную программу следующего образовательного уровня.
     *
     * Кандидаты: программы следующего уровня, у которых есть хотя бы одна
     * категория, совпадающая с категориями текущей программы.
     * Сортировка: пересечение категорий кандидата с топ-3 пользователя × 100
     *             + сумма баллов по пересекающимся категориям.
     */
    public function findNextLevelProgram(
        Program $program,
        Collection $scores,
        Collection $topCategories
    ): ?Program {
        $nextLevels = self::NEXT_LEVELS[$program->level] ?? [];

        if (empty($nextLevels)) {
            return null;
        }

        $currentCatIds = $program->categories->pluck('id');
        $topCatIds     = $topCategories->pluck('id');

        // Кандидаты — программы следующего уровня, пересекающиеся с текущей программой по категориям
        $candidates = Program::with(['institution', 'school', 'categories', 'professions'])
            ->whereIn('level', $nextLevels)
            ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $currentCatIds))
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        // Сортируем по релевантности к профилю пользователя (топ-3 категории + баллы)
        return $candidates->sortByDesc(function ($candidate) use ($topCatIds, $scores) {
            $candidateCatIds = $candidate->categories->pluck('id');
            $intersect       = $candidateCatIds->intersect($topCatIds);

            return $intersect->count() * 100
                 + $intersect->map(fn($id) => $scores->get($id, 0))->sum();
        })->first();
    }

    /**
     * Подготовить структурированные данные для карты карьерных путей.
     *
     * raw_score = (кол-во пересечений с топ-3 категориями) × 100
     *           + сумма баллов только по пересекающимся категориям.
     * Формула намеренно совпадает с базовой частью recommendPrograms(),
     * чтобы проценты на карте соответствовали порядку рекомендаций.
     */
    public function buildCareerMapData(
        TestSession $session,
        Collection $programs,
        Collection $scores,
        Collection $topCategories
    ): array {
        $startLabel = match($session->education) {
            '9_классов'  => '9 классов',
            '11_классов' => '11 классов',
            'СПО'        => 'Диплом СПО',
            'высшее'     => 'Высшее образование',
            default      => $session->education ?? '',
        };

        if ($topCategories->isEmpty() || $programs->isEmpty()) {
            return ['start_label' => $startLabel, 'programs' => collect()];
        }

        $topCatIds    = $topCategories->pluck('id');
        $pathPrograms = $programs->take(5)->values();

        $items = $pathPrograms->map(function ($program, $idx) use ($topCatIds, $scores, $topCategories, $session) {
            $programCatIds = $program->categories->pluck('id');
            $intersect     = $programCatIds->intersect($topCatIds);

            $rawScore = $intersect->count() * 100
                      + $intersect->map(fn($id) => $scores->get($id, 0))->sum();

            // Для пользователей, которые хотят выйти на работу быстро,
            // СПО уже является конечной точкой — не тянем следующий уровень
            $wantsFast   = $session->study_pace === 'быстро';
            $nextProgram = ($wantsFast && $program->level === 'СПО')
                ? null
                : $this->findNextLevelProgram($program, $scores, $topCategories);

            return [
                'program'          => $program,
                'raw_score'        => $rawScore,
                'is_top'           => $idx === 0,
                'next_program'     => $nextProgram,
                'next_level_label' => $nextProgram?->level,
            ];
        })->values();

        $maxRaw = $items->max('raw_score') ?: 1;

        $items = $items->map(fn($item) => array_merge($item, [
            'match_percent' => (int) round($item['raw_score'] / $maxRaw * 100),
        ]))->values();

        return [
            'start_label' => $startLabel,
            'programs'    => $items,
        ];
    }

    /**
     * Подобрать программы под результаты теста и данные анкеты.
     *
     * Порядок приоритетов:
     * 1. Уровень образования (жёсткий фильтр)
     * 2. Форма обучения: если выбрана конкретная — программы с совпадением
     *    идут выше остальных (двухуровневая сортировка)
     * 3. Внутри каждого уровня: релевантность по сферам + бонусы города/темпа
     */
    public function recommendPrograms(TestSession $session, Collection $scores): Collection
    {
        $topCategoryIds = $scores->take(3)->keys();
        $city           = $session->city;
        $studyFormPref  = ($session->study_form_pref && $session->study_form_pref !== 'любая')
            ? $session->study_form_pref : null;

        // Замыкание фильтра по уровню образования — используется в двух запросах
        $applyEducationFilter = function ($query) use ($session) {
            if ($session->education === '9_классов') {
                $query->whereIn('level', ['СПО']);
            } elseif ($session->education === '11_классов') {
                $query->whereIn('level', ['бакалавриат', 'специалитет', 'СПО']);
            } elseif ($session->education === 'СПО') {
                $query->whereIn('level', ['бакалавриат', 'специалитет']);
            } elseif ($session->education === 'высшее') {
                $query->whereIn('level', ['магистратура', 'аспирантура']);
            }
        };

        // Основной запрос: программы с пересечением по топ-3 сферам
        $baseQuery = Program::with(['institution', 'school', 'categories', 'professions'])
            ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $topCategoryIds));
        $applyEducationFilter($baseQuery);

        $programs = $baseQuery->get();

        // Fallback: если ни одна программа не совпала по сферам —
        // снимаем фильтр по категориям, оставляем только фильтр по образованию
        if ($programs->isEmpty()) {
            $fallbackQuery = Program::with(['institution', 'school', 'categories', 'professions']);
            $applyEducationFilter($fallbackQuery);
            $programs = $fallbackQuery->get();
        }

        // Скоринг: каждая программа получает балл релевантности.
        // Форма обучения здесь не участвует — она учитывается на следующем шаге
        // через явное разбиение на два уровня, чтобы совпадение формы не могло
        // быть перебито более высоким категорийным баллом.
        $programs = $programs->sortByDesc(function ($program) use ($topCategoryIds, $scores, $city, $session) {
            $programCatIds = $program->categories->pluck('id');
            $programCity   = $program->institution->city ?? '';

            // Релевантность по сферам (основной вес)
            $matches = $programCatIds->intersect($topCategoryIds)->count();

            // catScore: только по пересечению с топ-3
            $catScore = $programCatIds->intersect($topCategoryIds)
                ->map(fn($id) => $scores->get($id, 0))
                ->sum();

            // Предпочтение по городу
            $cityBonus = 0;
            if ($city) {
                if ($programCity === $city) {
                    $cityBonus = 50;
                } elseif ($session->relocation === 'нет') {
                    $cityBonus = -200;
                }
            }

            // Предпочтение по темпу обучения
            $paceBonus = 0;
            if ($session->study_pace === 'быстро') {
                if ($session->education === '11_классов' && $program->level === 'СПО') {
                    $paceBonus = 100;
                } elseif ($session->education !== 'высшее'
                    && in_array($program->level, ['магистратура', 'аспирантура'])) {
                    $paceBonus = -50;
                }
            } elseif ($session->study_pace === 'не_тороплюсь'
                && $session->education === '11_классов') {
                if (in_array($program->level, ['бакалавриат', 'специалитет'])) {
                    $paceBonus = 80;
                }
            }

            return $matches * 100 + $catScore + $cityBonus + $paceBonus;
        })->values();

        // Если пользователь категорически не хочет переезжать — скрываем чужие города,
        // но только если есть хоть что-то в его городе
        if ($city && $session->relocation === 'нет') {
            $localPrograms = $programs->filter(fn($p) => ($p->institution->city ?? '') === $city);
            if ($localPrograms->count() > 0) {
                $programs = $localPrograms->values();
            }
        }

        // Двухуровневая сортировка по форме обучения:
        // программы с точным совпадением формы идут первыми (внутри каждой группы
        // порядок по баллам уже задан выше), затем все остальные формы.
        if ($studyFormPref) {
            $exactForm = $programs->filter(fn($p) => $p->study_form === $studyFormPref)->values();
            $otherForm = $programs->filter(fn($p) => $p->study_form !== $studyFormPref)->values();
            $programs  = $exactForm->concat($otherForm)->values();
        }

        return $programs->take(10);
    }
}
