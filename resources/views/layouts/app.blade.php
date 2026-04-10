<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $documentTitle = trim($__env->yieldContent('title'));
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $documentTitle !== '' ? $documentTitle . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap (for existing CRUD pages that use Bootstrap classes) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F6F0E8] antialiased">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <main class="min-w-0 flex-1 p-4 pt-14 sm:ml-64 sm:p-5 sm:pt-5 md:p-5 lg:p-5">
            @hasSection('content')
                @yield('content')
            @elseif (isset($slot))
                {{ $slot }}
            @endif
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const forms = document.querySelectorAll('form.needs-validation');

            forms.forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
