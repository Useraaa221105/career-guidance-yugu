<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Institution;
use App\Models\Profession;
use App\Models\Program;
use App\Models\Question;
use App\Models\School;
use App\Models\TestBlock;
use App\Models\TestSession;
use App\Models\TestSessionAnswer;
use App\Models\TestSessionBlock;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'questions'    => Question::where('is_active', true)->count(),
            'programs'     => Program::count(),
            'professions'  => Profession::count(),
            'institutions' => Institution::count(),
            'schools'      => School::count(),
            'categories'   => Category::count(),
            'users'        => User::where('role', 'user')->count(),
            'results'      => TestSession::whereHas('sessionBlocks', fn($q) =>
                $q->whereHas('block', fn($b) => $b->where('slug', 'sklon'))
                  ->whereNotNull('completed_at')
            )->count(),
        ];

        return view('admin.index', compact('stats'));
    }

    // ───── Пользователи ────────────────────────────────────────────────────────

    public function users(): View
    {
        $users = User::withCount('testSessions')
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('created_at')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Нельзя удалить администратора.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя удалить свой собственный аккаунт.');
        }

        $email = $user->email;

        $sessionIds = TestSession::where('user_id', $user->id)->pluck('id');

        if ($sessionIds->isNotEmpty()) {
            TestSessionAnswer::whereIn('test_session_id', $sessionIds)->delete();
            TestSessionBlock::whereIn('test_session_id', $sessionIds)->delete();
            TestSession::whereIn('id', $sessionIds)->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь {$email} и его диагностические данные удалены.");
    }

    public function questions(): View
    {
        $blocks = TestBlock::with([
            'questions' => fn($q) => $q->orderBy('order_index'),
            'questions.answers' => fn($q) => $q->orderBy('order_index'),
            'questions.answers.categories',
        ])
            ->where('slug', '!=', 'anketa')
            ->orderBy('order_index')
            ->get();

        return view('admin.questions.index', compact('blocks'));
    }

    public function createQuestion(TestBlock $block): View
    {
        abort_if($block->slug === 'anketa', 403, 'Нельзя добавлять вопросы в блок «Анкета».');

        $nextOrder = ($block->questions()->max('order_index') ?? 0) + 1;

        return view('admin.questions.create', compact('block', 'nextOrder'));
    }

    public function storeQuestion(Request $request, TestBlock $block): RedirectResponse
    {
        abort_if($block->slug === 'anketa', 403, 'Нельзя добавлять вопросы в блок «Анкета».');

        $data = $request->validate([
            'text'        => 'required|string|max:1000',
            'order_index' => 'required|integer|min:1|max:9999',
            'is_active'   => 'nullable|boolean',
        ]);

        $question = Question::create([
            'block_id'    => $block->id,
            'text'        => $data['text'],
            'order_index' => (int) $data['order_index'],
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.questions.edit', $question)
            ->with('success', 'Вопрос создан. Добавьте варианты ответов и назначьте им баллы, затем активируйте.');
    }

    public function editQuestion(Question $question): View
    {
        $question->load('block');

        return view('admin.questions.edit', compact('question'));
    }

    public function updateQuestion(Request $request, Question $question): RedirectResponse
    {
        $data = $request->validate([
            'text'        => 'required|string|max:1000',
            'order_index' => 'required|integer|min:1|max:9999',
            'is_active'   => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');

        $activeAnswerCount = $question->answers()->where('is_active', true)->count();

        $question->update([
            'text'        => $data['text'],
            'order_index' => (int) $data['order_index'],
            'is_active'   => $isActive,
        ]);

        $message = 'Вопрос сохранён.';
        if ($isActive && $activeAnswerCount < 2) {
            $message .= ' Внимание: у вопроса менее 2 активных ответов — пользователи не увидят его в тесте корректно.';
        }

        return redirect()->route('admin.questions.index')
            ->with('success', $message);
    }

    public function editAnswer(Answer $answer): View
    {
        $answer->load(['question.block', 'categories']);

        $block = $answer->question->block;

        // Маппинг slug блока → тип категорий
        $blockTypeMap = [
            'sklon'    => 'sphere',
            'intellect' => 'intellect',
            'values'   => 'value',
        ];
        $categoryType = $blockTypeMap[$block->slug] ?? null;

        if ($categoryType) {
            $categories = Category::where('type', $categoryType)->orderBy('name')->get();
        } else {
            $categories = Category::orderBy('type')->orderBy('name')->get();
        }

        $existingPoints = $answer->categories->pluck('pivot.points', 'id');

        return view('admin.answers.edit', compact('answer', 'categories', 'existingPoints', 'block'));
    }

    public function updateAnswer(Request $request, Answer $answer): RedirectResponse
    {
        $request->validate([
            'text'     => 'required|string|max:1000',
            'is_active' => 'nullable|boolean',
            'points'   => 'nullable|array',
            'points.*' => 'nullable|integer|min:0|max:5',
        ]);

        $isActive = $request->boolean('is_active');
        $rawPoints = $request->input('points', []);

        // Оставляем только положительные баллы
        $pointsToSync = collect($rawPoints)
            ->filter(fn($p) => $p !== null && (int) $p > 0)
            ->mapWithKeys(fn($p, $id) => [(int) $id => ['points' => (int) $p]])
            ->toArray();

        if ($isActive && empty($pointsToSync)) {
            return back()
                ->withErrors(['points' => 'Активный ответ должен иметь хотя бы одну категорию с баллами больше 0.'])
                ->withInput();
        }

        $answer->update([
            'text'      => $request->input('text'),
            'is_active' => $isActive,
        ]);

        $answer->categories()->sync($pointsToSync);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Ответ сохранён.');
    }

    public function storeAnswer(Request $request, Question $question): RedirectResponse
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $maxOrder = $question->answers()->max('order_index') ?? 0;

        $newAnswer = $question->answers()->create([
            'text'        => $request->input('text'),
            'order_index' => $maxOrder + 1,
            'is_active'   => false,
        ]);

        return redirect()->route('admin.answers.edit', $newAnswer)
            ->with('success', 'Ответ добавлен. Укажите категории и баллы, затем активируйте.');
    }

    public function destroyAnswer(Answer $answer): RedirectResponse
    {
        $used = \App\Models\TestSessionAnswer::where('answer_id', $answer->id)->exists();

        if ($used) {
            return redirect()->route('admin.questions.index')
                ->with('warning', 'Ответ уже использовался в результатах пользователей. Его нельзя удалить, но можно отключить.');
        }

        $answer->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Ответ удалён.');
    }

    public function destroyQuestion(Question $question): RedirectResponse
    {
        $answerIds = $question->answers()->pluck('id');

        if ($answerIds->isNotEmpty()) {
            $used = \App\Models\TestSessionAnswer::whereIn('answer_id', $answerIds)->exists();

            if ($used) {
                return redirect()->route('admin.questions.index')
                    ->with('warning', 'Вопрос уже использовался в результатах пользователей. Его нельзя удалить, но можно отключить.');
            }
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Вопрос удалён.');
    }

    // ───── Учреждения ──────────────────────────────────────────────────────────

    public function institutions(): View
    {
        $institutions = Institution::withCount(['programs', 'schools'])
            ->orderBy('name')
            ->get();

        return view('admin.institutions.index', compact('institutions'));
    }

    public function createInstitution(): View
    {
        return view('admin.institutions.create');
    }

    public function storeInstitution(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'short_name'  => 'nullable|string|max:100',
            'city'        => 'required|string|max:100',
            'type'        => 'required|in:университет,филиал,колледж,техникум,институт',
            'description' => 'nullable|string|max:1000',
        ]);

        $institution = Institution::create($data);

        return redirect()
            ->route('admin.institutions.edit', $institution)
            ->with('success', 'Учреждение «' . $institution->name . '» создано.');
    }

    public function editInstitution(Institution $institution): View
    {
        $institution->loadCount(['programs', 'schools'])->load('schools:id,institution_id,name');

        return view('admin.institutions.edit', compact('institution'));
    }

    public function updateInstitution(Request $request, Institution $institution): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'short_name'  => 'nullable|string|max:100',
            'city'        => 'required|string|max:100',
            'type'        => 'required|in:университет,филиал,колледж,техникум,институт',
            'description' => 'nullable|string|max:1000',
        ]);

        $institution->update($data);

        return redirect()
            ->route('admin.institutions.index')
            ->with('success', 'Учреждение «' . $institution->name . '» сохранено.');
    }

    // ───── Школы / подразделения ────────────────────────────────────────────────

    public function schools(): View
    {
        $schools = School::withCount('programs')
            ->with('institution:id,name,short_name')
            ->orderBy('institution_id')
            ->orderBy('name')
            ->get();

        return view('admin.schools.index', compact('schools'));
    }

    public function createSchool(): View
    {
        $institutions = Institution::orderBy('name')->get(['id', 'name', 'short_name']);

        return view('admin.schools.create', compact('institutions'));
    }

    public function storeSchool(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'institution_id' => 'required|exists:institutions,id',
            'description'    => 'nullable|string|max:1000',
        ]);

        $school = School::create($data);

        return redirect()
            ->route('admin.schools.edit', $school)
            ->with('success', 'Подразделение «' . $school->name . '» создано.');
    }

    public function editSchool(School $school): View
    {
        $school->loadCount('programs');
        $institutions = Institution::orderBy('name')->get(['id', 'name', 'short_name']);

        return view('admin.schools.edit', compact('school', 'institutions'));
    }

    public function updateSchool(Request $request, School $school): RedirectResponse
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'institution_id' => 'required|exists:institutions,id',
            'description'    => 'nullable|string|max:1000',
        ]);

        $school->update($data);

        return redirect()
            ->route('admin.schools.index')
            ->with('success', 'Подразделение «' . $school->name . '» сохранено.');
    }

    public function categories(): View
    {
        $groups = Category::withCount(['answers', 'programs'])
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        $typeLabels = [
            'sphere'    => 'Сферы деятельности',
            'intellect' => 'Типы интеллекта',
            'value'     => 'Жизненные ценности',
        ];

        return view('admin.categories.index', compact('groups', 'typeLabels'));
    }

    public function editCategory(Category $category): View
    {
        $category->loadCount(['answers', 'programs']);

        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon'        => 'nullable|string|max:20',
            'color'       => ['nullable', 'string', 'max:50', 'regex:/^#[0-9a-fA-F]{3,8}$/'],
        ]);

        // type намеренно не принимается из формы — fillable модели его не содержит
        $category->update([
            'name'        => trim($request->input('name')),
            'description' => $request->input('description') ? trim($request->input('description')) : null,
            'icon'        => $request->input('icon') ? trim($request->input('icon')) : null,
            'color'       => $request->input('color') ?: null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Категория «' . $category->name . '» сохранена.');
    }

    public function professions(): View
    {
        $professions = Profession::withCount('programs')
            ->with(['programs' => fn($q) => $q->select('programs.id', 'programs.name', 'programs.level', 'programs.institution_id')
                ->with('institution:id,short_name,name')
                ->orderBy('programs.name')
            ])
            ->orderBy('name')
            ->paginate(50)
            ->withQueryString();

        return view('admin.professions.index', compact('professions'));
    }

    public function createProfession(): View
    {
        return view('admin.professions.create');
    }

    public function storeProfession(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:professions,name',
        ]);

        $profession = Profession::create(['name' => trim($request->input('name'))]);

        return redirect()
            ->route('admin.professions.edit', $profession)
            ->with('success', 'Профессия «' . $profession->name . '» создана. При необходимости привяжите программы.');
    }

    public function editProfession(Profession $profession): View
    {
        $profession->load(['programs.institution:id,short_name,name']);

        $institutions = Institution::with([
            'programs' => fn($q) => $q->select('programs.id', 'programs.name', 'programs.level', 'programs.institution_id')->orderBy('programs.name'),
        ])->orderBy('name')->get();

        $selectedProgramIds = $profession->programs->pluck('id')->all();

        return view('admin.professions.edit', compact('profession', 'institutions', 'selectedProgramIds'));
    }

    public function updateProfession(Request $request, Profession $profession): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255', Rule::unique('professions', 'name')->ignore($profession->id)],
            'programs'   => 'nullable|array',
            'programs.*' => 'integer|exists:programs,id',
        ]);

        $profession->update(['name' => trim($request->input('name'))]);
        $profession->programs()->sync($request->input('programs', []));

        return redirect()
            ->route('admin.professions.index')
            ->with('success', 'Профессия «' . $profession->name . '» сохранена.');
    }

    public function programs(Request $request): View
    {
        $search        = trim($request->input('search', ''));
        $levelFilter   = $request->input('level', '');
        $formFilter    = $request->input('study_form', '');
        $instFilter    = $request->input('institution_id', '');
        $catFilter     = $request->input('category_id', '');

        $programQuery = Program::query()
            ->with(['school', 'categories', 'professions'])
            ->orderBy('level')
            ->orderBy('name');

        if ($search !== '') {
            $programQuery->where(function ($q) use ($search) {
                $q->where('programs.name', 'like', "%{$search}%")
                  ->orWhere('programs.code', 'like', "%{$search}%")
                  ->orWhereHas('institution', fn($iq) =>
                      $iq->where('name', 'like', "%{$search}%")
                         ->orWhere('short_name', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%")
                  );
            });
        }

        if ($levelFilter !== '') {
            $programQuery->where('level', $levelFilter);
        }

        if ($formFilter !== '') {
            $programQuery->where('study_form', $formFilter);
        }

        if ($instFilter !== '') {
            $programQuery->where('institution_id', $instFilter);
        }

        if ($catFilter !== '') {
            $programQuery->whereHas('categories', fn($q) => $q->where('categories.id', $catFilter));
        }

        $allPrograms = $programQuery->get();
        $totalCount  = $allPrograms->count();

        $grouped = $allPrograms->groupBy('institution_id');

        $institutions = Institution::whereIn('id', $grouped->keys())
            ->get()
            ->map(function ($inst) use ($grouped) {
                $inst->setRelation('programs', $grouped->get($inst->id, collect()));
                return $inst;
            })
            ->sortByDesc(fn($inst) => $inst->programs->count())
            ->values();

        $filterInstitutions  = Institution::orderBy('name')->get();
        $filterCategories    = Category::where('type', 'sphere')->orderBy('name')->get();
        $isFiltered = $search !== '' || $levelFilter !== '' || $formFilter !== '' || $instFilter !== '' || $catFilter !== '';

        return view('admin.programs.index', compact(
            'institutions', 'totalCount', 'isFiltered',
            'filterInstitutions', 'filterCategories',
            'search', 'levelFilter', 'formFilter', 'instFilter', 'catFilter'
        ));
    }

    public function createProgram(): View
    {
        $institutions     = Institution::orderBy('name')->get();
        $schools          = School::with('institution')->orderBy('institution_id')->orderBy('name')->get();
        $sphereCategories = Category::where('type', 'sphere')->orderBy('name')->get();
        $professions      = Profession::orderBy('name')->get();

        return view('admin.programs.create', compact(
            'institutions', 'schools', 'sphereCategories', 'professions'
        ));
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code'           => 'required|string|max:50',
            'name'           => 'required|string|max:500',
            'level'          => 'required|in:СПО,бакалавриат,специалитет,магистратура,аспирантура',
            'study_form'     => 'required|in:очная,заочная,очно-заочная',
            'description'    => 'nullable|string|max:2000',
            'institution_id' => 'required|exists:institutions,id',
            'school_id'      => 'nullable|exists:schools,id',
            'categories'     => 'nullable|array',
            'categories.*'   => 'integer|exists:categories,id',
            'professions'    => 'nullable|array',
            'professions.*'  => 'integer|exists:professions,id',
            'new_profession' => 'nullable|string|max:200',
        ]);

        $sphereIds = Category::where('type', 'sphere')->pluck('id');
        $catToSync = collect($data['categories'] ?? [])
            ->filter(fn($id) => $sphereIds->contains((int) $id))
            ->map(fn($id) => (int) $id)
            ->values()->all();

        if (empty($catToSync)) {
            return back()
                ->withErrors(['categories' => 'Выберите хотя бы одну сферу деятельности.'])
                ->withInput();
        }

        $program = Program::create([
            'code'           => $data['code'],
            'name'           => $data['name'],
            'level'          => $data['level'],
            'study_form'     => $data['study_form'],
            'description'    => $data['description'] ?? null,
            'institution_id' => (int) $data['institution_id'],
            'school_id'      => isset($data['school_id']) ? (int) $data['school_id'] : null,
        ]);

        $program->categories()->sync($catToSync);

        $profIds = array_map('intval', $data['professions'] ?? []);
        if (!empty($data['new_profession'])) {
            $newProf   = Profession::firstOrCreate(['name' => trim($data['new_profession'])]);
            $profIds[] = $newProf->id;
        }
        $program->professions()->sync(array_unique($profIds));

        return redirect()
            ->route('admin.programs.edit', $program)
            ->with('success', 'Программа «' . $program->name . '» создана. Проверьте и дополните данные.');
    }

    public function editProgram(Program $program): View
    {
        $program->load(['institution', 'school', 'categories', 'professions']);

        $institutions       = Institution::orderBy('name')->get();
        $schools            = School::with('institution')->orderBy('institution_id')->orderBy('name')->get();
        $sphereCategories   = Category::where('type', 'sphere')->orderBy('name')->get();
        $professions        = Profession::orderBy('name')->get();
        $selectedCatIds     = $program->categories->pluck('id')->all();
        $selectedProfIds    = $program->professions->pluck('id')->all();

        return view('admin.programs.edit', compact(
            'program', 'institutions', 'schools',
            'sphereCategories', 'professions',
            'selectedCatIds', 'selectedProfIds'
        ));
    }

    public function updateProgram(Request $request, Program $program): RedirectResponse
    {
        $data = $request->validate([
            'code'           => 'required|string|max:50',
            'name'           => 'required|string|max:500',
            'level'          => 'required|in:СПО,бакалавриат,специалитет,магистратура,аспирантура',
            'study_form'     => 'required|in:очная,заочная,очно-заочная',
            'description'    => 'nullable|string|max:2000',
            'institution_id' => 'required|exists:institutions,id',
            'school_id'      => 'nullable|exists:schools,id',
            'categories'     => 'nullable|array',
            'categories.*'   => 'integer|exists:categories,id',
            'professions'    => 'nullable|array',
            'professions.*'  => 'integer|exists:professions,id',
            'new_profession' => 'nullable|string|max:200',
        ]);

        // Проверяем, что выбрана хотя бы одна сфера (type='sphere')
        $sphereIds = Category::where('type', 'sphere')->pluck('id');
        $catToSync = collect($data['categories'] ?? [])
            ->filter(fn($id) => $sphereIds->contains((int) $id))
            ->map(fn($id) => (int) $id)
            ->values()->all();

        if (empty($catToSync)) {
            return back()
                ->withErrors(['categories' => 'Выберите хотя бы одну сферу деятельности.'])
                ->withInput();
        }

        $program->update([
            'code'           => $data['code'],
            'name'           => $data['name'],
            'level'          => $data['level'],
            'study_form'     => $data['study_form'],
            'description'    => $data['description'] ?? null,
            'institution_id' => (int) $data['institution_id'],
            'school_id'      => isset($data['school_id']) ? (int) $data['school_id'] : null,
        ]);

        $program->categories()->sync($catToSync);

        // Профессии: существующие + новая (если введена)
        $profIds = array_map('intval', $data['professions'] ?? []);

        if (!empty($data['new_profession'])) {
            $newProf   = Profession::firstOrCreate(['name' => trim($data['new_profession'])]);
            $profIds[] = $newProf->id;
        }

        $program->professions()->sync(array_unique($profIds));

        return redirect()->route('admin.programs.index')
            ->with('success', 'Программа «' . $program->name . '» сохранена.');
    }

    public function destroyProfession(Profession $profession): RedirectResponse
    {
        if ($profession->programs()->count() > 0) {
            return redirect()->route('admin.professions.index')
                ->with('error', 'Нельзя удалить профессию «' . $profession->name . '»: она привязана к образовательным программам. Сначала уберите привязки.');
        }

        $name = $profession->name;
        $profession->delete();

        return redirect()->route('admin.professions.index')
            ->with('success', 'Профессия «' . $name . '» удалена.');
    }

    public function destroyProgram(Program $program): RedirectResponse
    {
        $name = $program->name;

        $program->categories()->detach();
        $program->professions()->detach();
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Программа «' . $name . '» удалена.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        $category->loadCount(['answers', 'programs']);

        if ($category->answers_count > 0 || $category->programs_count > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Нельзя удалить категорию «' . $category->name . '»: она используется в ответах теста или образовательных программах.');
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория «' . $name . '» удалена.');
    }

    public function results(Request $request): View
    {
        $search     = trim($request->input('search', ''));
        $education  = $request->input('education', '');
        $city       = $request->input('city', '');
        $relocation = $request->input('relocation', '');
        $blocks     = $request->input('blocks', '');

        $query = TestSession::with(['user', 'sessionBlocks.block'])
            ->whereHas('sessionBlocks', fn($q) =>
                $q->whereHas('block', fn($b) => $b->where('slug', 'sklon'))
                  ->whereNotNull('completed_at')
            );

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('test_sessions.name', 'like', "%{$search}%")
                  ->orWhere('test_sessions.city', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) =>
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                  );
            });
        }

        if ($education !== '') {
            $query->where('education', $education);
        }

        if ($city !== '') {
            $query->where('city', $city);
        }

        if ($relocation === 'yes') {
            $query->where('relocation', 'да');
        } elseif ($relocation === 'no') {
            $query->where('relocation', 'нет');
        }

        if ($blocks === 'sklon') {
            // Только склонности: нет завершённых intellect и values
            $query->whereDoesntHave('sessionBlocks', fn($q) =>
                $q->whereHas('block', fn($b) => $b->whereIn('slug', ['intellect', 'values']))
                  ->whereNotNull('completed_at')
            );
        } elseif ($blocks === 'sklon_intellect') {
            // Склонности + интеллект, но не ценности
            $query->whereHas('sessionBlocks', fn($q) =>
                    $q->whereHas('block', fn($b) => $b->where('slug', 'intellect'))
                      ->whereNotNull('completed_at')
                )
                ->whereDoesntHave('sessionBlocks', fn($q) =>
                    $q->whereHas('block', fn($b) => $b->where('slug', 'values'))
                      ->whereNotNull('completed_at')
                );
        } elseif ($blocks === 'sklon_values') {
            // Склонности + ценности, но не интеллект
            $query->whereHas('sessionBlocks', fn($q) =>
                    $q->whereHas('block', fn($b) => $b->where('slug', 'values'))
                      ->whereNotNull('completed_at')
                )
                ->whereDoesntHave('sessionBlocks', fn($q) =>
                    $q->whereHas('block', fn($b) => $b->where('slug', 'intellect'))
                      ->whereNotNull('completed_at')
                );
        } elseif ($blocks === 'all') {
            // Все диагностические блоки
            $query->whereHas('sessionBlocks', fn($q) =>
                    $q->whereHas('block', fn($b) => $b->where('slug', 'intellect'))
                      ->whereNotNull('completed_at')
                )
                ->whereHas('sessionBlocks', fn($q) =>
                    $q->whereHas('block', fn($b) => $b->where('slug', 'values'))
                      ->whereNotNull('completed_at')
                );
        }

        $sessions = $query->latest()->paginate(30)->withQueryString();

        $availableCities = TestSession::whereHas('sessionBlocks', fn($q) =>
            $q->whereHas('block', fn($b) => $b->where('slug', 'sklon'))
              ->whereNotNull('completed_at')
        )->whereNotNull('city')->where('city', '!=', '')->distinct()->orderBy('city')->pluck('city');

        $isFiltered = $search !== '' || $education !== '' || $city !== '' || $relocation !== '' || $blocks !== '';

        return view('admin.results.index', compact(
            'sessions', 'availableCities', 'isFiltered',
            'search', 'education', 'city', 'relocation', 'blocks'
        ));
    }
}
