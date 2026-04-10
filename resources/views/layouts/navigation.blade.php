@php
    $currentRole = strtolower(auth()->user()->role ?? 'admin');
    $isDashboard = request()->routeIs('dashboard');
    $isProducts = request()->routeIs('products.*');
    $isPurchases =
        request()->routeIs('purchases.index') ||
        request()->routeIs('purchases.export') ||
        request()->routeIs('purchases.receipt') ||
        request()->routeIs('purchases.receipt.pdf');
    $isPurchaseCreate = request()->routeIs('purchases.create') || request()->routeIs('purchases.member');
    $isUsers = request()->routeIs('users.*');
    $isSystemSettings = request()->routeIs('system-settings.*');
    $isProfile = request()->routeIs('profile.*');
@endphp

<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
    type="button"
    class="fixed left-3 top-3 z-50 inline-flex rounded-lg border border-transparent bg-white/80 p-2 text-[#1F1B16] shadow-sm transition hover:bg-[#F8F2E8] focus:outline-none focus:ring-4 focus:ring-[#DB5F22]/20 sm:hidden">
    <span class="sr-only">Open sidebar</span>
    <svg class="h-6 w-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
        viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
    </svg>
</button>

<aside id="default-sidebar"
    class="fixed left-0 top-0 z-40 h-full w-64 -translate-x-full border-e border-white/10 bg-gradient-to-b from-[#1F1B16] to-[#2B1F18] text-white transition-transform sm:translate-x-0"
    aria-label="Sidebar">
    <div class="flex h-full flex-col overflow-y-auto px-3 py-4">
        <div class="mb-6 px-2">
            <h2 class="text-[24px] font-bold tracking-tight">Alfa Beta</h2>
            <p class="mt-1 text-[13px] text-white/65">Flow Notion Mode</p>
        </div>

        <ul class="space-y-2 text-[15px] font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center rounded-lg px-2 py-1.5 transition {{ $isDashboard ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/90 hover:bg-white/10 hover:text-[#FDEBDD]' }}">
                    <svg class="h-5 w-5 shrink-0 transition duration-75 {{ $isDashboard ? 'text-white' : 'text-white/70 group-hover:text-[#F09A63]' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            @if (Route::has('products.index'))
                <li>
                    <a href="{{ route('products.index') }}"
                        class="group flex items-center rounded-lg px-2 py-1.5 transition {{ $isProducts ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/90 hover:bg-white/10 hover:text-[#FDEBDD]' }}">
                        <svg class="h-5 w-5 shrink-0 transition duration-75 {{ $isProducts ? 'text-white' : 'text-white/70 group-hover:text-[#F09A63]' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
                        </svg>
                        <span class="ms-3">Produk</span>
                    </a>
                </li>
            @endif

            @if (Route::has('purchases.index'))
                <li>
                    <a href="{{ route('purchases.index') }}"
                        class="group flex items-center rounded-lg px-2 py-1.5 transition {{ $isPurchases ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/90 hover:bg-white/10 hover:text-[#FDEBDD]' }}">
                        <svg class="h-5 w-5 shrink-0 transition duration-75 {{ $isPurchases ? 'text-white' : 'text-white/70 group-hover:text-[#F09A63]' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 13h3.439a.991.991 0 0 1 .908.6 3.978 3.978 0 0 0 7.306 0 .99.99 0 0 1 .908-.6H20M4 13v6a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-6M4 13l2-9h12l2 9M9 7h6m-7 3h8" />
                        </svg>
                        <span class="ms-3">Pembelian</span>
                    </a>
                </li>
            @endif

            @if ($currentRole === 'petugas' && Route::has('purchases.create'))
                <li>
                    <a href="{{ route('purchases.create') }}"
                        class="group flex items-center rounded-lg px-2 py-1.5 transition {{ $isPurchaseCreate ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/90 hover:bg-white/10 hover:text-[#FDEBDD]' }}">
                        <svg class="h-5 w-5 shrink-0 transition duration-75 {{ $isPurchaseCreate ? 'text-white' : 'text-white/70 group-hover:text-[#F09A63]' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14m-7 7V5" />
                        </svg>
                        <span class="ms-3">Tambah Pembelian</span>
                    </a>
                </li>
            @endif

            @if (Route::has('users.index'))
                <li>
                    <a href="{{ route('users.index') }}"
                        class="group flex items-center rounded-lg px-2 py-1.5 transition {{ $isUsers ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/90 hover:bg-white/10 hover:text-[#FDEBDD]' }}">
                        <svg class="h-5 w-5 shrink-0 transition duration-75 {{ $isUsers ? 'text-white' : 'text-white/70 group-hover:text-[#F09A63]' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="ms-3">Data Pengguna</span>
                    </a>
                </li>
            @endif

            @if ($currentRole === 'admin' && Route::has('system-settings.edit'))
                <li>
                    <a href="{{ route('system-settings.edit') }}"
                        class="group flex items-center rounded-lg px-2 py-1.5 transition {{ $isSystemSettings ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/90 hover:bg-white/10 hover:text-[#FDEBDD]' }}">
                        <svg class="h-5 w-5 shrink-0 transition duration-75 {{ $isSystemSettings ? 'text-white' : 'text-white/70 group-hover:text-[#F09A63]' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>
                        <span class="ms-3">System Settings</span>
                    </a>
                </li>
            @endif
        </ul>

        <ul class="mt-auto space-y-2 border-t border-white/10 pt-4 text-[15px] font-medium">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="group flex w-full items-center rounded-lg px-2 py-1.5 text-left text-white/90 transition hover:bg-white/10 hover:text-[#FDEBDD]">
                        <svg class="h-5 w-5 shrink-0 text-white/70 transition duration-75 group-hover:text-[#F09A63]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
                        </svg>
                        <span class="ms-3">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
