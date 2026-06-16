<nav class="bg-base-100 h-full flex items-center">
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
            <div class="indicator">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>

                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-xs badge-error indicator-item"></span>
                @endif
            </div>
        </div>

        <div tabindex="0"
            class="mt-3 z-20 card card-compact dropdown-content w-80 bg-base-100 shadow-2xl border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-bold text-base">Notifikasi</h3>
                    <span class="text-xs opacity-50">{{ auth()->user()->unreadNotifications->count() }} pesan
                        baru</span>
                </div>

                <div class="flex flex-col gap-1 max-h-96 overflow-y-auto mt-2">
                    @forelse(auth()->user()->notifications->take(10) as $notification)
                        <div
                            class="p-3 rounded-lg border-b last:border-0 hover:bg-base-200 transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-primary/5 border-l-4 border-l-primary' }}">
                            <div class="flex flex-col gap-1">
                                <p class="text-sm leading-tight text-base-content">
                                    {{ $notification->data['pesan'] }}
                                </p>
                                <div class="flex justify-between items-center mt-2">
                                    <span
                                        class="text-[10px] opacity-50">{{ $notification->created_at->diffForHumans() }}</span>
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                        class="text-[10px] font-bold text-primary hover:underline">
                                        Tandai Baca
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto opacity-20 mb-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-xs opacity-50">Belum ada riwayat notifikasi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

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
