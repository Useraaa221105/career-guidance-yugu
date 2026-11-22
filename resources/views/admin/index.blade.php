<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Панель администратора
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-xl px-5 py-3">
                {{ session('info') }}
            </div>
            @endif

            {{-- Приветствие --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Управление сервисом</h1>
                <p class="mt-1 text-gray-500 text-sm">
                    Здесь вы можете настраивать диагностику, управлять программами, профессиями,
                    категориями и просматривать результаты пользователей.
                </p>
            </div>

            {{-- Сетка карточек --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- Вопросы и баллы --}}
                <a href="{{ route('admin.questions.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">🎯</div>
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $stats['questions'] }} вопросов
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Вопросы и баллы
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Блоки, вопросы, варианты ответов и баллы по категориям
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Образовательные программы --}}
                <a href="{{ route('admin.programs.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">🎓</div>
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $stats['programs'] }} программ
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Образовательные программы
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Программы вузов и колледжей с привязкой к категориям и профессиям
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Результаты пользователей --}}
                <a href="{{ route('admin.results.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">📊</div>
                        <span class="text-xs font-medium bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                            {{ $stats['results'] }} результатов
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Результаты пользователей
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Прохождения диагностики, ссылки на результаты и аналитика
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Профессии --}}
                <a href="{{ route('admin.professions.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">💼</div>
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $stats['professions'] }} профессий
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Профессии
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Справочник профессий и привязка к образовательным программам
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Категории --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">🏷️</div>
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $stats['categories'] }} категорий
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Категории
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Сферы деятельности, типы интеллекта и жизненные ценности
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Учреждения --}}
                <a href="{{ route('admin.institutions.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">🏛️</div>
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $stats['institutions'] }} учреждений
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Учреждения
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Образовательные организации — вузы, колледжи, институты
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Школы / подразделения --}}
                <a href="{{ route('admin.schools.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">🏫</div>
                        <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">
                            {{ $stats['schools'] }} подразделений
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                            Школы / подразделения
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Структурные подразделения учреждений — факультеты, институты
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

                {{-- Пользователи --}}
                <a href="{{ route('admin.users.index') }}"
                   class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4 hover:shadow-md hover:border-red-200 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="text-3xl">👤</div>
                        <span class="text-xs font-medium bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">
                            {{ $stats['users'] }} пользователей
                        </span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800 group-hover:text-red-700 transition-colors">
                            Пользователи
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Список аккаунтов, роли, удаление тестовых пользователей
                        </p>
                    </div>
                    <div class="mt-auto flex items-center text-sm font-semibold text-red-600 group-hover:gap-2 gap-1 transition-all">
                        Открыть <span>→</span>
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
