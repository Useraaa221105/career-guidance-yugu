<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $session = \App\Models\TestSession::where('user_id', auth()->id())
        ->with(['sessionBlocks.block'])
        ->latest()
        ->first();

    return view('dashboard', compact('session'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Админ-панель (только для роли admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          [AdminController::class, 'index'])->name('index');
    // Учреждения
    Route::get('/institutions',                      [AdminController::class, 'institutions'])->name('institutions.index');
    Route::get('/institutions/create',               [AdminController::class, 'createInstitution'])->name('institutions.create');
    Route::post('/institutions',                     [AdminController::class, 'storeInstitution'])->name('institutions.store');
    Route::get('/institutions/{institution}/edit',   [AdminController::class, 'editInstitution'])->name('institutions.edit');
    Route::patch('/institutions/{institution}',      [AdminController::class, 'updateInstitution'])->name('institutions.update');

    // Школы / подразделения
    Route::get('/schools',                   [AdminController::class, 'schools'])->name('schools.index');
    Route::get('/schools/create',            [AdminController::class, 'createSchool'])->name('schools.create');
    Route::post('/schools',                  [AdminController::class, 'storeSchool'])->name('schools.store');
    Route::get('/schools/{school}/edit',     [AdminController::class, 'editSchool'])->name('schools.edit');
    Route::patch('/schools/{school}',        [AdminController::class, 'updateSchool'])->name('schools.update');

    // Категории
    Route::get('/categories',                    [AdminController::class, 'categories'])->name('categories.index');
    Route::get('/categories/{category}/edit',    [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::patch('/categories/{category}',       [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}',      [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // Профессии
    Route::get('/professions',                       [AdminController::class, 'professions'])->name('professions.index');
    Route::get('/professions/create',                [AdminController::class, 'createProfession'])->name('professions.create');
    Route::post('/professions',                      [AdminController::class, 'storeProfession'])->name('professions.store');
    Route::get('/professions/{profession}/edit',     [AdminController::class, 'editProfession'])->name('professions.edit');
    Route::patch('/professions/{profession}',        [AdminController::class, 'updateProfession'])->name('professions.update');
    Route::delete('/professions/{profession}',       [AdminController::class, 'destroyProfession'])->name('professions.destroy');

    Route::get('/programs',                  [AdminController::class, 'programs'])->name('programs.index');
    Route::get('/programs/create',           [AdminController::class, 'createProgram'])->name('programs.create');
    Route::post('/programs',                 [AdminController::class, 'storeProgram'])->name('programs.store');
    Route::get('/programs/{program}/edit',   [AdminController::class, 'editProgram'])->name('programs.edit');
    Route::patch('/programs/{program}',      [AdminController::class, 'updateProgram'])->name('programs.update');
    Route::delete('/programs/{program}',     [AdminController::class, 'destroyProgram'])->name('programs.destroy');
    Route::get('/results',   [AdminController::class, 'results'])->name('results.index');

    // Пользователи
    Route::get('/users',             [AdminController::class, 'users'])->name('users.index');
    Route::delete('/users/{user}',   [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Вопросы
    Route::get('/questions',                     [AdminController::class, 'questions'])->name('questions.index');
    Route::get('/questions/{question}/edit',     [AdminController::class, 'editQuestion'])->name('questions.edit');
    Route::patch('/questions/{question}',        [AdminController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{question}',       [AdminController::class, 'destroyQuestion'])->name('questions.destroy');
    Route::post('/questions/{question}/answers', [AdminController::class, 'storeAnswer'])->name('answers.store');

    // Создание вопроса в блоке
    Route::get('/blocks/{block}/questions/create', [AdminController::class, 'createQuestion'])->name('questions.create');
    Route::post('/blocks/{block}/questions',       [AdminController::class, 'storeQuestion'])->name('questions.store');

    // Ответы
    Route::get('/answers/{answer}/edit',  [AdminController::class, 'editAnswer'])->name('answers.edit');
    Route::patch('/answers/{answer}',     [AdminController::class, 'updateAnswer'])->name('answers.update');
    Route::delete('/answers/{answer}',    [AdminController::class, 'destroyAnswer'])->name('answers.destroy');
});

// Диагностика профориентации (только для обычных пользователей)
Route::middleware(['auth', 'user-only'])->group(function () {
    Route::get('/test',                             [TestController::class, 'index'])->name('test.index');
    Route::post('/test/start',                      [TestController::class, 'start'])->name('test.start');
    Route::get('/test/diagnostics',                 [TestController::class, 'diagnostics'])->name('test.diagnostics');
    Route::post('/test/block/{block}/reset',         [TestController::class, 'resetBlock'])->name('test.block.reset');
    Route::get('/test/block/{block}/{number}',       [TestController::class, 'question'])->name('test.question');
    Route::post('/test/block/{block}/{number}',      [TestController::class, 'answer'])->name('test.answer');
    Route::get('/test/anketa/edit',                 [TestController::class, 'editAnketa'])->name('test.anketa.edit');
    Route::patch('/test/anketa/update',             [TestController::class, 'updateAnketa'])->name('test.anketa.update');
});

// Результаты доступны без авторизации (по токену — для шаринга)
Route::get('/test/results/{token}', [TestController::class, 'results'])->name('test.results');

require __DIR__.'/auth.php';
