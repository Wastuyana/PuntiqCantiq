<x-guest-layout>
    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="card-body">
            <h2 class="card-title text-2xl font-bold mb-4">Login</h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-control w-full">
                    <label class="label font-semibold">
                        <span class="label-text mb-1">Email Address</span>
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="user@example.com"
                        class="px-2 input input-bordered w-full focus:input-primary @error('email') input-error @enderror"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <div class="form-control w-full mt-4">
                    <label class="label font-semibold">
                        <span class="label-text mb-1">Password</span>
                    </label>
                    <input id="password" type="password" name="password" placeholder="••••••••"
                        class="px-2 input input-bordered w-full focus:input-primary @error('password') input-error @enderror"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <div class="form-control">
                        <label class="label cursor-pointer gap-2 p-0">
                            <input type="checkbox" id="remember_me" name="remember"
                                class="checkbox checkbox-sm checkbox-primary rounded" />
                            <span class="label-text text-sm">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="link link-hover link-primary text-sm" href="{{ route('password.request') }}">
                            {{ __('Forgot?') }}
                        </a>
                    @endif
                </div>

                <div class="mt-8">
                    <button type="submit" class="btn btn-primary w-full tracking-widest uppercase">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
