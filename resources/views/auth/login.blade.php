<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white mb-2">Welcome back</h2>
            <p class="text-dark-400">Sign in to your account to continue</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-white">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                            </path>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full pl-10 pr-4 py-3 bg-dark-700/50 border border-dark-600/50 rounded-xl text-white placeholder-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your email">
                </div>
                @error('email')
                    <div class="text-accent-400 text-sm flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-white">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full pl-10 pr-4 py-3 bg-dark-700/50 border border-dark-600/50 rounded-xl text-white placeholder-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your password">
                </div>
                @error('password')
                    <div class="text-accent-400 text-sm flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 text-primary-600 bg-dark-700 border-dark-600 rounded focus:ring-primary-500 focus:ring-2">
                    <span class="ml-2 text-sm text-dark-300">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-primary-400 hover:text-primary-300 transition-colors duration-200">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 hover:scale-[1.02] hover:shadow-colored">
                Sign In
            </button>
        </form>

        <!-- OR Divider -->
        <div class="relative mt-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-dark-600/50"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-dark-800/50 text-dark-400">or</span>
            </div>
        </div>

        <!-- Google Login Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('google.login') }}"
                class="inline-flex items-center justify-center w-full px-6 py-3 bg-white text-gray-800 font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 488 512" fill="currentColor">
                    <path
                        d="M488 261.8c0-17.8-1.6-35.2-4.7-52H249v98.5h134.7c-5.8 31.3-23.6 57.8-50.2 75.5v62h81.3c47.6-43.9 73.2-108.6 73.2-183.9z" />
                    <path
                        d="M249 512c67.6 0 124.2-22.4 165.6-60.5l-81.3-62c-22.5 15.1-51.2 24-84.3 24-64.8 0-119.6-43.7-139.3-102.3h-84.8v64.3C57.5 466.1 146.6 512 249 512z" />
                    <path
                        d="M109.7 311.2c-4.8-14.1-7.6-29.1-7.6-44.5s2.7-30.4 7.6-44.5v-64.3h-84.8C10.4 200.5 0 223.5 0 249s10.4 48.5 24.9 67.6l84.8-5.4z" />
                    <path
                        d="M249 97c35.4 0 67.2 12.2 92.2 36.2l69-69C369.1 23.3 312.4 0 249 0 146.6 0 57.5 45.9 24.9 117.4l84.8 64.3C129.4 140.7 184.2 97 249 97z" />
                </svg>
                Sign in with Google
            </a>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-dark-600/50"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-dark-800/50 text-dark-400">Don't have an account?</span>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <a href="{{ route('register') }}"
                class="inline-flex items-center px-6 py-3 border border-primary-600/50 text-primary-400 hover:text-white hover:bg-primary-600/20 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                Create new account
            </a>
        </div>
    </div>
</x-guest-layout>
