<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm font-medium text-zinc-800 dark:text-white">
                    {{ __('Email address') }}
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username email"
                    placeholder="email@example.com"
                    class="w-full border rounded-lg py-2 px-3 text-base sm:text-sm bg-white dark:bg-white/10 text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 border-zinc-300 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-[#d1ab58] focus:border-transparent transition"
                />
                @error('email')
                    <span class="text-sm text-red-500 dark:text-red-400">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-2" x-data="{ showPassword: false }">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-sm font-medium text-zinc-800 dark:text-white">
                        {{ __('Password') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-[#d1ab58] hover:underline">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div class="relative w-full">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="{{ __('Password') }}"
                        class="w-full border rounded-lg py-2 ps-3 pe-11 text-base sm:text-sm bg-white dark:bg-white/10 text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 border-zinc-300 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-[#d1ab58] focus:border-transparent transition"
                    />

                    <button
                        type="button"
                        id="toggle-password-btn"
                        @click="showPassword = !showPassword"
                        onclick="if(!window.Alpine){ const p=document.getElementById('password'); const isPwd=p.type==='password'; p.type=isPwd?'text':'password'; this.querySelector('.eye-open').classList.toggle('hidden', !isPwd); this.querySelector('.eye-closed').classList.toggle('hidden', isPwd); }"
                        class="absolute inset-y-0 end-0 flex items-center pe-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition focus:outline-none"
                        aria-label="Pokaż lub ukryj hasło"
                        tabindex="-1"
                    >
                        {{-- Eye Open (shows when password is text) --}}
                        <svg class="size-5 eye-open hidden" :class="{ 'hidden': !showPassword }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                        {{-- Eye Slash / Closed (shows when password is hidden) --}}
                        <svg class="size-5 eye-closed" :class="{ 'hidden': showPassword }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-sm text-red-500 dark:text-red-400">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    value="1"
                    {{ old('remember') ? 'checked' : '' }}
                    class="size-4 rounded border-zinc-300 dark:border-white/10 text-[#d1ab58] focus:ring-[#d1ab58] dark:bg-white/10 cursor-pointer accent-[#d1ab58]"
                />
                <label for="remember" class="text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer select-none">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="flex items-center justify-end">
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-lg font-medium text-sm text-zinc-950 bg-[#d1ab58] hover:bg-[#c29c49] transition shadow-xs focus:outline-none focus:ring-2 focus:ring-[#d1ab58] focus:ring-offset-2 font-semibold"
                    data-test="login-button"
                >
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a href="{{ route('register') }}" wire:navigate class="font-medium text-[#d1ab58] hover:underline">{{ __('Sign up') }}</a>
            </div>
        @endif
    </div>
</x-layouts::auth>
