<nav class="bg-base-100 h-full flex items-center">
    <div class="dropdown dropdown-end">

        <label tabindex="0" class="btn btn-ghost btn-sm lg:btn-md flex items-center gap-2 font-medium normal-case">
            <span>{{ Auth::user()->name }}</span>
            <svg class="fill-current h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <ul tabindex="0"
            class="dropdown-content z-1 menu p-2 shadow-xl bg-base-100 rounded-box w-52 border border-base-300 mt-2">
            <li>
                <a href="{{ route('profile.edit') }}" class="flex justify-between">
                    {{ __('Profile') }}
                </a>
            </li>

            <div class="divider my-1"></div>

            <li>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="text-error flex justify-between w-full">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
