<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Alfa Beta') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#F8F2E8] text-[#1F1B16] [font-family:'Plus_Jakarta_Sans',sans-serif]">
    <header class="px-3 pb-2 pt-4">
        <div class="mx-auto w-full max-w-[1240px] rounded-[26px] border border-[#1F1B16]/15 bg-white px-4 py-3 md:px-6">
            <div class="flex items-center justify-between gap-3">
                <a href="#" class="flex items-center gap-3" aria-label="Alfa Beta">
                    <span
                        class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#e7743c] to-[#DB5F22] shadow-[0_8px_18px_rgba(219,95,34,0.3)]">
                        <span class="h-1 w-4 rounded-full bg-white"></span>
                        <span class="-mt-1 h-1 w-3 rounded-full bg-white"></span>
                    </span>
                    <span
                        class="text-[24px] font-bold tracking-[-0.04em] md:text-[30px] [font-family:'Sora',sans-serif]">Alfa
                        Beta</span>
                </a>

                <nav class="hidden flex-1 items-center justify-center gap-10 lg:flex" aria-label="Menu utama">
                    <a href="#"
                        class="text-[17px] font-bold tracking-[-0.02em] text-[#1F1B16]/70 transition hover:text-[#1F1B16] xl:text-[22px]">Fitur</a>
                    <a href="#"
                        class="text-[17px] font-bold tracking-[-0.02em] text-[#1F1B16]/70 transition hover:text-[#1F1B16] xl:text-[22px]">Harga</a>
                    <a href="#"
                        class="text-[17px] font-bold tracking-[-0.02em] text-[#1F1B16]/70 transition hover:text-[#1F1B16] xl:text-[22px]">Testimoni</a>
                </nav>

                <a href="{{ route('login') }}"
                    class="inline-flex h-11 items-center justify-center rounded-full border border-transparent bg-gradient-to-br from-[#DB5F22] to-[#eb915b] px-4 text-sm font-extrabold text-white shadow-[0_12px_20px_rgba(219,95,34,0.28)] transition hover:-translate-y-0.5 md:h-12 md:px-6 md:text-lg">Coba
                    Gratis</a>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-[1240px] px-3 pb-3 pt-2">
        <section
            class="rounded-[28px] border border-[#1F1B16]/15 bg-gradient-to-br from-[#FFFDFC] to-[#FCF9F4] p-5 shadow-[0_28px_48px_rgba(31,27,22,0.07)] md:p-8 xl:p-10"
            aria-label="Hero Alfa Beta">
            <div class="mx-auto max-w-[900px] text-center">
                <span
                    class="mx-auto inline-flex w-fit items-center rounded-full border border-[#DB5F22]/30 bg-[#DB5F22]/10 px-3 py-2 text-xs font-bold text-[#985026] md:px-4 md:text-sm">Aplikasi
                    kasir modern untuk admin dan petugas</span>

                <h1
                    class="mt-4 text-[40px] font-extrabold leading-[0.98] tracking-[-0.05em] text-[#1F1B16] md:text-[58px] xl:text-[70px] [font-family:'Sora',sans-serif]">
                    Kelola Toko Jadi <span class="text-[#DB5F22]">Lebih Mudah</span> &amp; Cepat
                </h1>

                <p
                    class="mx-auto mt-5 max-w-[860px] text-[15px] leading-[1.5] text-[#6f6458] md:text-[18px] xl:text-[20px]">
                    Alfa Beta membantu bisnis mengatur transaksi, stok, member, dan laporan dalam satu alur kerja yang
                    jelas.
                    Cocok untuk ujian proyek maupun kebutuhan operasional harian.
                </p>

                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('login') }}"
                        class="inline-flex h-12 items-center justify-center rounded-[14px] border border-transparent bg-gradient-to-br from-[#DB5F22] to-[#e77c45] px-6 text-sm font-extrabold tracking-[-0.02em] text-white transition hover:-translate-y-0.5 md:h-[60px] md:px-8 md:text-[20px]">Mulai
                        Sekarang</a>
                    <a href="#"
                        class="inline-flex h-12 items-center justify-center rounded-[14px] border border-[#1F1B16]/25 bg-white px-6 text-sm font-extrabold tracking-[-0.02em] text-[#1F1B16] transition hover:-translate-y-0.5 md:h-[60px] md:px-8 md:text-[20px]">Lihat
                        Demo</a>
                </div>

                <ul
                    class="mt-4 flex list-none flex-wrap items-center justify-center gap-4 text-[13px] font-semibold text-[#6f6458] md:text-[16px]">
                    <li class="inline-flex items-center gap-2">
                        <span
                            class="h-2.5 w-2.5 rounded-full bg-[#DB5F22] shadow-[0_0_0_4px_rgba(219,95,34,0.2)]"></span>
                        Tanpa Kartu Kredit
                    </li>
                    <li class="inline-flex items-center gap-2">
                        <span
                            class="h-2.5 w-2.5 rounded-full bg-[#DB5F22] shadow-[0_0_0_4px_rgba(219,95,34,0.2)]"></span>
                        Gratis 14 Hari
                    </li>
                </ul>
            </div>
        </section>

        <section class="pb-3 pt-3" aria-label="Keunggulan Alfa Beta">
            <div
                class="rounded-[24px] border border-[#DB5F22]/25 bg-gradient-to-br from-[#FCF8F0] to-[#F8F2E8] p-5 shadow-[0_20px_36px_rgba(31,27,22,0.06)] md:p-8">
                <p class="text-center text-[12px] font-extrabold uppercase tracking-[0.18em] text-[#DB5F22] md:text-sm">
                    Keunggulan Kami</p>
                <h2
                    class="mt-2 text-center text-[34px] font-extrabold leading-[1.05] tracking-[-0.03em] text-[#1F1B16] md:text-[44px] xl:text-[52px] [font-family:'Sora',sans-serif]">
                    Mengapa Memilih Alfa Beta?
                </h2>

                <div class="mt-5 grid gap-4 lg:grid-cols-3">
                    <article class="rounded-[18px] border border-[#DB5F22]/25 bg-[#FFF9F2] p-5">
                        <span class="mb-3 inline-grid h-11 w-11 place-items-center rounded-xl bg-[#DB5F22]/12">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#DB5F22]" aria-hidden="true">
                                <path d="M13 2L5 14h6l-1 8 9-13h-6l1-7z" />
                            </svg>
                        </span>
                        <h3
                            class="text-[24px] font-bold tracking-[-0.02em] text-[#1F1B16] md:text-[30px] [font-family:'Sora',sans-serif]">
                            Transaksi Kilat</h3>
                        <p class="mt-2 text-[15px] leading-[1.45] text-[#6f6458] md:text-[17px]">Proses pembayaran
                            kurang dari 5 detik dengan integrasi berbagai metode pembayaran digital.</p>
                    </article>

                    <article class="rounded-[18px] border border-[#DB5F22]/25 bg-[#FFF9F2] p-5">
                        <span class="mb-3 inline-grid h-11 w-11 place-items-center rounded-xl bg-[#DB5F22]/12">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#DB5F22]" aria-hidden="true">
                                <path d="M4 5h16v14H4V5zm3 3v2h10V8H7zm0 4v4h3v-4H7zm5 0v4h5v-4h-5z" />
                            </svg>
                        </span>
                        <h3
                            class="text-[24px] font-bold tracking-[-0.02em] text-[#1F1B16] md:text-[30px] [font-family:'Sora',sans-serif]">
                            Manajemen Stok</h3>
                        <p class="mt-2 text-[15px] leading-[1.45] text-[#6f6458] md:text-[17px]">Update stok otomatis
                            secara real-time dan notifikasi saat barang mulai menipis.</p>
                    </article>

                    <article class="rounded-[18px] border border-[#DB5F22]/25 bg-[#FFF9F2] p-5">
                        <span class="mb-3 inline-grid h-11 w-11 place-items-center rounded-xl bg-[#DB5F22]/12">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#DB5F22]" aria-hidden="true">
                                <path d="M6 3h10l4 4v14H6V3zm9 1.5V8h3.5L15 4.5zM8 11h10v2H8v-2zm0 4h10v2H8v-2z" />
                            </svg>
                        </span>
                        <h3
                            class="text-[24px] font-bold tracking-[-0.02em] text-[#1F1B16] md:text-[30px] [font-family:'Sora',sans-serif]">
                            Laporan Akurat</h3>
                        <p class="mt-2 text-[15px] leading-[1.45] text-[#6f6458] md:text-[17px]">Pantau omzet harian
                            hingga bulanan dengan grafik yang mudah dimengerti dari mana saja.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-2 bg-[#1F1B16]">
        <div
            class="mx-auto grid w-full max-w-[1240px] gap-8 px-3 py-8 text-[#F4E6D3] md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr]">
            <section>
                <a href="#" class="flex items-center gap-3" aria-label="Alfa Beta">
                    <span
                        class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#e7743c] to-[#DB5F22] shadow-[0_8px_18px_rgba(219,95,34,0.3)]">
                        <span class="h-1 w-4 rounded-full bg-white"></span>
                        <span class="-mt-1 h-1 w-3 rounded-full bg-white"></span>
                    </span>
                    <span
                        class="text-[28px] font-bold tracking-[-0.04em] text-white md:text-[32px] [font-family:'Sora',sans-serif]">Alfa
                        Beta</span>
                </a>
                <p class="mt-3 max-w-[580px] text-[16px] text-[#F4E6D3]/90 md:text-[18px]">
                    Memberdayakan jutaan pengusaha Indonesia dengan teknologi kasir yang handal dan mudah digunakan.
                </p>

                <ul class="mt-4 flex list-none items-center gap-2">
                    <li><a href="#"
                            class="inline-grid h-9 w-9 place-items-center rounded-full border border-[#F4E6D3]/25 bg-white/5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:border-[#DB5F22]">f</a>
                    </li>
                    <li><a href="#"
                            class="inline-grid h-9 w-9 place-items-center rounded-full border border-[#F4E6D3]/25 bg-white/5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:border-[#DB5F22]">ig</a>
                    </li>
                    <li><a href="#"
                            class="inline-grid h-9 w-9 place-items-center rounded-full border border-[#F4E6D3]/25 bg-white/5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:border-[#DB5F22]">in</a>
                    </li>
                </ul>
            </section>

            <section>
                <h3 class="text-[24px] font-bold text-white [font-family:'Sora',sans-serif]">Perusahaan</h3>
                <a href="#"
                    class="mt-2 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Tentang
                    Kami</a>
                <a href="#"
                    class="mt-1 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Karir</a>
                <a href="#"
                    class="mt-1 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Blog</a>
                <a href="#"
                    class="mt-1 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Kontak</a>
            </section>

            <section>
                <h3 class="text-[24px] font-bold text-white [font-family:'Sora',sans-serif]">Bantuan</h3>
                <a href="#"
                    class="mt-2 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Pusat
                    Bantuan</a>
                <a href="#"
                    class="mt-1 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Panduan
                    Pengguna</a>
                <a href="#"
                    class="mt-1 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Syarat
                    &amp; Ketentuan</a>
                <a href="#"
                    class="mt-1 block text-[17px] text-[#F4E6D3]/95 transition hover:text-white md:text-[18px]">Kebijakan
                    Privasi</a>
            </section>
        </div>
    </footer>
</body>

</html>
