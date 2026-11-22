<x-guest-layout>
    {{-- Заголовок --}}
    <div class="mb-7 text-center">
        <h1 class="text-xl font-bold text-gray-900">Регистрация</h1>
        <p class="text-sm text-gray-400 mt-1">Создайте аккаунт для доступа к сервису</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Имя --}}
        <div>
            <x-input-label for="name" value="Имя" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                          :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Почта --}}
        <div>
            <x-input-label for="email" value="Почта" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Пароль --}}
        <div>
            <x-input-label for="password" value="Пароль" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                          name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Подтверждение пароля --}}
        <div>
            <x-input-label for="password_confirmation" value="Подтвердите пароль" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Кнопка --}}
        <button type="submit"
                class="w-full py-2.5 text-sm font-semibold text-white rounded-lg hover:opacity-90 transition"
                style="background-color:#1B52CC;">
            Зарегистрироваться
        </button>

        {{-- Ссылка на вход --}}
        <p class="text-center text-sm text-gray-500">
            Уже есть аккаунт?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:#1B52CC;">
                Войти
            </a>
        </p>
    </form>
</x-guest-layout>
