<div class="w-64 bg-white border-r min-h-screen relative">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-gray-800">URL Shortener</h2>

        @auth
            <p class="text-sm text-gray-500">
                {{ auth()->user()->role->name ?? '' }}
            </p>
        @endauth
    </div>

    {{-- NAV --}}
    <nav class="mt-4 space-y-1 px-3">

        @auth
        <a href="{{ route('dashboard') }}"
           class="block px-3 py-2 rounded
           {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
            Dashboard
        </a>
        @endauth
        @auth
        @if(auth()->user()->isSuperAdmin())

            <a href="{{ route('users.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                User Management
            </a>
            <a href="{{ route('invite.form') }}"
               class="block px-3 py-2 rounded
               {{ request()->routeIs('invite.*') ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
                Invite User
            </a>

            <a href="{{ route('urls.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                Short URLs
            </a>

        @elseif(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                User Management
            </a>
            <a href="{{ route('invite.form') }}"
               class="block px-3 py-2 rounded
               {{ request()->routeIs('invite.*') ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
                Invite User
            </a>

            <a href="{{ route('urls.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                Short URLs
            </a>

        @elseif(auth()->user()->isMember())
            <a href="{{ route('urls.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                Short URLs
            </a>

        @endif
        @endauth

        @auth
        <a href="{{ route('profile.edit') }}"
           class="block px-3 py-2 rounded hover:bg-gray-100">
            Profile
        </a>
        @endauth
    </nav>

    {{-- LOGOUT --}}
    @auth
    <div class="absolute bottom-0 w-64 border-t px-3 py-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left text-red-600 hover:bg-red-50 px-3 py-2 rounded">
                Logout
            </button>
        </form>
    </div>
    @endauth

</div>
