<?php

namespace App\Http\Controllers;

use App\Models\TestBlock;
use App\Models\TestSession;
use App\Models\TestSessionAnswer;
use App\Models\TestSessionBlock;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TestController extends Controller
{
    public function __construct(private ScoringService $scoring) {}

    // Анкета (если нет активной сессии) или redirect на дашборд
    public function index()
    {
        $session = $this->getActiveSession();
        if ($session) {
            return redirect()->route('test.diagnostics');
        }
        return view('test.index');
    }

    // Сохранить анкету и начать диагностику
    public function start(Request $request)
    {
        // Если у пользователя уже есть сессия — не создаём новую
        $existing = $this->getActiveSession();
        if ($existing) {
            return redirect()->route('test.diagnostics');
        }

        $data = $request->validate([
            'city'             => 'nullable|string|max:100',
            'education'        => 'required|in:9_классов,11_классов,СПО,высшее',
            'study_pace'       => 'required|in:быстро,не_тороплюсь',
            'study_form_pref'  => 'required|in:очная,заочная,очно-заочная,любая',
            'name'             => 'nullable|string|max:100',
            'age'              => 'nullable|integer|min:10|max:99',
            'gender'           => 'nullable|in:мужской,женский,не_указывать',
            'institution_type' => 'nullable|in:школа,колледж,университет,не_обучаюсь',
            'budget'           => 'nullable|in:бюджет,платно',
            'relocation'       => 'nullable|in:да,возможно,нет',
            'dormitory'        => 'nullable|in:да,возможно,нет',
            'ege_status'       => 'nullable|in:определился,выбираю,примерно,не_определился,уже_сдавал',
        ]);

        $session = TestSession::create([
            'user_id'          => auth()->id(),
            'token'            => Str::random(40),
            'city'             => $data['city'] ?? null,
            'education'        => $data['education'],
            'study_pace'       => $data['study_pace'],
            'study_form_pref'  => $data['study_form_pref'],
            'name'             => $data['name'] ?? null,
            'age'              => $data['age'] ?? null,
            'gender'           => $data['gender'] ?? null,
            'institution_type' => $data['institution_type'] ?? null,
            'budget'           => $data['budget'] ?? null,
            'relocation'       => $data['relocation'] ?? null,
            'dormitory'        => $data['dormitory'] ?? null,
            'ege_status'       => $data['ege_status'] ?? null,
        ]);

        // Помечаем блок «Анкета» как пройденный
        $anketaBlock = TestBlock::where('slug', 'anketa')->first();
        if ($anketaBlock) {
            TestSessionBlock::create([
                'test_session_id' => $session->id,
                'test_block_id'   => $anketaBlock->id,
                'completed_at'    => now(),
            ]);
        }

        session(['test_session_id' => $session->id]);

        return redirect()->route('test.diagnostics');
    }

    // Дашборд с прогрессом по блокам
    public function diagnostics()
    {
        $session = $this->getActiveSession();
        if (!$session) {
            return redirect()->route('test.index');
        }

        $blocks = TestBlock::orderBy('order_index')->get();
        $completedSlugs = $session->completedBlockSlugs();

        // Все ли тестовые блоки (не анкета) пройдены
        $testBlocks = $blocks->filter(fn($b) => $b->slug !== 'anketa');
        $allDone = $testBlocks->every(fn($b) => in_array($b->slug, $completedSlugs));

        return view('test.diagnostics', compact('blocks', 'completedSlugs', 'session', 'allDone'));
    }

    // Показать вопрос блока
    public function question(string $block, int $number)
    {
        $session = $this->getActiveSession();
        if (!$session) return redirect()->route('test.index');

        $testBlock = TestBlock::where('slug', $block)->firstOrFail();

        // Если блок уже пройден — на дашборд
        if (in_array($block, $session->completedBlockSlugs())) {
            return redirect()->route('test.diagnostics');
        }

        // Только активные вопросы с хотя бы одним активным ответом
        $questions = $testBlock->questions()
            ->where('is_active', true)
            ->whereHas('answers', fn($q) => $q->where('is_active', true))
            ->get();
        $total = $questions->count();

        if ($number < 1 || $number > $total) {
            return redirect()->route('test.diagnostics');
        }

        $question = $questions[$number - 1];
        $activeAnswers = $question->activeAnswers;

        return view('test.question', [
            'question'     => $question,
            'activeAnswers' => $activeAnswers,
            'block'        => $testBlock,
            'number'       => $number,
            'total'        => $total,
            'progress'     => round(($number - 1) / $total * 100),
        ]);
    }

    // Сохранить ответ на вопрос блока
    public function answer(Request $request, string $block, int $number)
    {
        $session = $this->getActiveSession();
        if (!$session) return redirect()->route('test.index');

        $testBlock = TestBlock::where('slug', $block)->firstOrFail();
        $questions = $testBlock->questions()
            ->where('is_active', true)
            ->whereHas('answers', fn($q) => $q->where('is_active', true))
            ->get();
        $total = $questions->count();

        if ($number < 1 || $number > $total) {
            return redirect()->route('test.diagnostics');
        }

        $question = $questions[$number - 1];

        $request->validate([
            'answer_id' => [
                'required',
                Rule::exists('answers', 'id')
                    ->where('question_id', $question->id)
                    ->where('is_active', true),
            ],
        ]);

        $answer = $question->answers()->findOrFail($request->answer_id);

        TestSessionAnswer::updateOrCreate(
            ['test_session_id' => $session->id, 'question_id' => $question->id],
            ['answer_id' => $answer->id]
        );

        if ($number < $total) {
            return redirect()->route('test.question', [$block, $number + 1]);
        }

        // Последний вопрос блока — помечаем как пройденный
        TestSessionBlock::updateOrCreate(
            ['test_session_id' => $session->id, 'test_block_id' => $testBlock->id],
            ['completed_at' => now()]
        );

        return redirect()->route('test.diagnostics');
    }

    // Страница результатов
    public function results(string $token)
    {
        $session = TestSession::where('token', $token)
            ->with(['sessionAnswers.answer.categories'])
            ->firstOrFail();

        // Проверяем что хотя бы блок 'sklon' пройден
        $completedSlugs = $session->completedBlockSlugs();
        if (!in_array('sklon', $completedSlugs)) {
            return redirect()->route('test.diagnostics');
        }

        // Склонности
        $scores        = $this->scoring->calculateScores($session);
        $topCategories = $this->scoring->getTopCategories($scores);
        $programs      = $this->scoring->recommendPrograms($session, $scores);

        $allCategories = \App\Models\Category::all()->keyBy('id');
        $scoreData = $scores->map(fn($points, $id) => [
            'name'   => $allCategories[$id]->name,
            'points' => $points,
            'color'  => $allCategories[$id]->color,
            'icon'   => $allCategories[$id]->icon,
        ])->values();

        // Тип интеллекта
        $topIntelligence = null;
        $intelligenceData = collect();
        if (in_array('intellect', $completedSlugs)) {
            $intellectScores  = $this->scoring->calculateIntelligenceScores($session);
            $topIntelligence  = $this->scoring->getTopIntelligenceType($intellectScores);
            $intellectMaxScore = $intellectScores->max() ?: 1;
            $intelligenceData = $intellectScores->map(fn($points, $id) => [
                'name'     => $allCategories[$id]->name,
                'points'   => $points,
                'color'    => $allCategories[$id]->color,
                'icon'     => $allCategories[$id]->icon,
                'percent'  => round($points / $intellectMaxScore * 100),
            ])->values()->sortByDesc('points');
        }

        // Ценности
        $topValues = collect();
        if (in_array('values', $completedSlugs)) {
            $valueScores = $this->scoring->calculateValueScores($session);
            $topValues   = $this->scoring->getTopValues($valueScores);
        }

        $careerMapData = $this->scoring->buildCareerMapData(
            $session, $programs, $scores, $topCategories
        );

        return view('test.results', [
            'session'          => $session,
            'topCategories'    => $topCategories,
            'programs'         => $programs,
            'scores'           => $scores,
            'scoreData'        => $scoreData,
            'topIntelligence'  => $topIntelligence,
            'intelligenceData' => $intelligenceData,
            'topValues'        => $topValues,
            'completedSlugs'   => $completedSlugs,
            'careerMapData'    => $careerMapData,
        ]);
    }

    // Сбросить блок и пройти заново
    public function resetBlock(string $block)
    {
        $session = $this->getActiveSession();
        if (!$session) {
            return redirect()->route('test.index');
        }

        $testBlock = TestBlock::where('slug', $block)->firstOrFail();

        // Удаляем отметку о прохождении блока
        TestSessionBlock::where('test_session_id', $session->id)
            ->where('test_block_id', $testBlock->id)
            ->delete();

        // Удаляем ответы на вопросы этого блока
        $questionIds = $testBlock->questions()->pluck('id');
        TestSessionAnswer::where('test_session_id', $session->id)
            ->whereIn('question_id', $questionIds)
            ->delete();

        return redirect()->route('test.question', [$block, 1]);
    }

    // Редактирование анкеты
    public function editAnketa()
    {
        $session = $this->getActiveSession();
        if (!$session) {
            return redirect()->route('test.index');
        }
        return view('test.edit-anketa', compact('session'));
    }

    // Сохранить изменения анкеты
    public function updateAnketa(Request $request)
    {
        $session = $this->getActiveSession();
        if (!$session) {
            return redirect()->route('test.index');
        }

        $data = $request->validate([
            'city'             => 'nullable|string|max:100',
            'education'        => 'required|in:9_классов,11_классов,СПО,высшее',
            'study_pace'       => 'required|in:быстро,не_тороплюсь',
            'study_form_pref'  => 'required|in:очная,заочная,очно-заочная,любая',
            'name'             => 'nullable|string|max:100',
            'age'              => 'nullable|integer|min:10|max:99',
            'gender'           => 'nullable|in:мужской,женский,не_указывать',
            'institution_type' => 'nullable|in:школа,колледж,университет,не_обучаюсь',
            'budget'           => 'nullable|in:бюджет,платно',
            'relocation'       => 'nullable|in:да,возможно,нет',
            'dormitory'        => 'nullable|in:да,возможно,нет',
            'ege_status'       => 'nullable|in:определился,выбираю,примерно,не_определился,уже_сдавал',
        ]);

        $session->update([
            'city'             => $data['city'] ?? null,
            'education'        => $data['education'],
            'study_pace'       => $data['study_pace'],
            'study_form_pref'  => $data['study_form_pref'],
            'name'             => $data['name'] ?? null,
            'age'              => $data['age'] ?? null,
            'gender'           => $data['gender'] ?? null,
            'institution_type' => $data['institution_type'] ?? null,
            'budget'           => $data['budget'] ?? null,
            'relocation'       => $data['relocation'] ?? null,
            'dormitory'        => $data['dormitory'] ?? null,
            'ege_status'       => $data['ege_status'] ?? null,
        ]);

        return redirect()->route('test.diagnostics')->with('status', 'Анкета обновлена');
    }

    private function getActiveSession(): ?TestSession
    {
        // Для авторизованных — основная сессия всегда определяется по user_id
        if (auth()->check()) {
            $session = TestSession::where('user_id', auth()->id())->latest()->first();
            if ($session) {
                session(['test_session_id' => $session->id]);
                return $session;
            }
            return null;
        }

        // Гости не проходят тест (маршруты защищены 'auth'), но fallback оставляем
        $id = session('test_session_id');
        if (!$id) return null;
        return TestSession::find($id);
    }
}
