<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta content="Fahim Anzam Dip" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    @include('includes.main-css')
    <link rel="stylesheet" href="{{ asset('css/responsive-tables.css') }}">
    
    <script>
        // Deteksi device dan tambahkan class yang sesuai
        function detectDevice() {
            let isMobile = window.matchMedia("(max-width: 767px)").matches;
            let isTablet = window.matchMedia("(min-width: 768px) and (max-width: 1024px)").matches;
            
            document.body.classList.remove('is-desktop', 'is-mobile', 'is-tablet');
            
            if (isMobile) {
                document.body.classList.add('is-mobile');
            } else if (isTablet) {
                document.body.classList.add('is-tablet');
            } else {
                document.body.classList.add('is-desktop');
            }
        }

        // Jalankan saat halaman dimuat
        window.addEventListener('load', detectDevice);
        // Jalankan saat ukuran window berubah
        window.addEventListener('resize', detectDevice);
    </script>
</head>

<body class="c-app">
    @include('layouts.sidebar')

    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed">
            @include('layouts.header')
            @if(!request()->routeIs('home'))
            <div class="c-subheader d-none d-md-block px-3">
                @yield('breadcrumb')
            </div>
            @endif
        </header>

        <div class="c-body">
            <main class="c-main">
                @yield('content')
            </main>
        </div>

        @include('layouts.footer')
    </div>

    @include('includes.main-js')
</body>
</html>
