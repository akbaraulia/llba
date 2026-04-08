@php
    $currentRole = strtolower(auth()->user()->role ?? 'admin');
@endphp

<aside
    class="panel-sidebar w-full bg-gradient-to-b from-[#1F1B16] to-[#2B1F18] px-6 py-6 text-white lg:min-h-screen lg:w-[290px] lg:shrink-0">
    <div class="lg:sticky lg:top-6">
        <h2 class="text-[24px] font-bold tracking-tight">Alfa Beta</h2>
        <p class="mt-1 text-[13px] text-white/65">Flow Notion Mode</p>

        <nav class="mt-6 space-y-2 text-[15px] font-semibold leading-tight">
            <a href="{{ route('dashboard') }}"
                class="block rounded-2xl px-4 py-2 transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/95 hover:bg-white/10' }}">
                Dashboard
            </a>

            @if (Route::has('products.index'))
                <a href="{{ route('products.index') }}"
                    class="block rounded-2xl px-4 py-2 transition {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/95 hover:bg-white/10' }}">
                    Produk
                </a>
            @endif

            @if (Route::has('purchases.index'))
                <a href="{{ route('purchases.index') }}"
                    class="block rounded-2xl px-4 py-2 transition {{ request()->routeIs('purchases.index') || request()->routeIs('purchases.export') || request()->routeIs('purchases.receipt') || request()->routeIs('purchases.receipt.pdf') ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/95 hover:bg-white/10' }}">
                    Pembelian
                </a>
            @endif

            @if ($currentRole === 'petugas' && Route::has('purchases.create'))
                <a href="{{ route('purchases.create') }}"
                    class="block rounded-2xl px-4 py-2 transition {{ request()->routeIs('purchases.create') || request()->routeIs('purchases.member') ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/95 hover:bg-white/10' }}">
                    Tambah Pembelian
                </a>
            @endif

            @if (Route::has('users.index'))
                <a href="{{ route('users.index') }}"
                    class="block rounded-2xl px-4 py-2 transition {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/95 hover:bg-white/10' }}">
                    Data Pengguna
                </a>
            @endif

            @if ($currentRole === 'admin' && Route::has('system-settings.edit'))
                <a href="{{ route('system-settings.edit') }}"
                    class="block rounded-2xl px-4 py-2 transition {{ request()->routeIs('system-settings.*') ? 'bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]' : 'text-white/95 hover:bg-white/10' }}">
                    System Settings
                </a>
            @endif
        </nav>
    </div>
</aside>
