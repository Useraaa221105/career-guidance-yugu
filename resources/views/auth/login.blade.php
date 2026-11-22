<x-guest-layout>
    {{-- Заголовок --}}
    <div class="mb-7 text-center">
        <h1 class="text-xl font-bold text-gray-900">Вход в систему</h1>
        <p class="text-sm text-gray-400 mt-1">Введите данные своей учётной записи</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Почта --}}
        <div>
            <x-input-label for="email" value="Почта" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Пароль --}}
        <div>
            <x-input-label for="password" value="Пароль" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                          name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Запомнить --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 shadow-sm"
                       style="accent-color:#1B52CC;">
                <span class="text-sm text-gray-600">Запомнить меня</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-sm hover:underline" style="color:#1B52CC;">
                Забыли пароль?
            </a>
            @endif
        </div>

        {{-- Кнопка --}}
        <button type="submit"
                class="w-full py-2.5 text-sm font-semibold text-white rounded-lg hover:opacity-90 transition"
                style="background-color:#1B52CC;">
            Войти
        </button>

        {{-- Ссылка на регистрацию --}}
        <p class="text-center text-sm text-gray-500">
            Нет аккаунта?
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:#1B52CC;">
                Зарегистрироваться
            </a>
        </p>
    </form>
</x-guest-layout>
