<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Пользователи
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Навигация --}}
            <div>
                <a href="{{ route('admin.index') }}"
                   class="text-sm text-indigo-600 hover:underline">← Панель администратора</a>
            </div>

            {{-- Flash-сообщения --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-5 py-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Заголовок --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Список пользователей</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Обычных пользователей (role=user) можно удалить вместе с их диагностическими данными.
                        Администраторов удалить нельзя.
                    </p>
                </div>
                <span class="text-sm text-gray-400">
                    {{ $users->count() }} {{ $users->count() === 1 ? 'пользователь' : ($users->count() < 5 ? 'пользователя' : 'пользователей') }}
                </span>
            </div>

            {{-- Таблица --}}
            @if ($users->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
                    <div class="text-4xl mb-3">👤</div>
                    <p class="text-lg font-medium text-gray-700">Пользователей нет</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Пользователь</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Роль</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Зарегистрирован</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Диагностика</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Имя + email --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $user->name ?: '—' }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>

                                    {{-- Роль --}}
                                    <td class="px-6 py-4">
                                        @if ($user->isAdmin())
                                            <span class="inline-flex items-center text-xs font-semibold
                                                         bg-amber-50 text-amber-700 border border-amber-200
                                                         px-2.5 py-1 rounded-full">
                                                👑 admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-xs font-medium
                                                         bg-gray-100 text-gray-600
                                                         px-2.5 py-1 rounded-full">
                                                user
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Дата регистрации --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $user->created_at->format('d.m.Y') }}
                                    </td>

                                    {{-- Диагностика --}}
                                    <td class="px-6 py-4">
                                        @if ($user->test_sessions_count > 0)
                                            <span class="inline-flex items-center text-xs font-medium
                                                         bg-indigo-50 text-indigo-700 border border-indigo-200
                                                         px-2.5 py-1 rounded-full">
                                                есть
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">нет</span>
                                        @endif
                                    </td>

                                    {{-- Действия --}}
                                    <td class="px-6 py-4 text-right">
                                        @if (!$user->isAdmin() && $user->id !== auth()->id())
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Удалить пользователя {{ addslashes($user->email) }} и все его диагностические данные? Это действие нельзя отменить.')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                                                    Удалить
                                                </button>
                                            </form>
                                        @elseif ($user->id === auth()->id())
                                            <span class="text-xs text-gray-400">вы</span>
                                        @else
                                            <span class="text-xs text-gray-300">защищён</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
