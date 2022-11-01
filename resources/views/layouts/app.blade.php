<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        .navbar-nav li:hover > ul.dropdown-menu {
            display: block;
        }
    </style>
</head>
<body>
    <div id="app">
        <div id="navbar-menu">
            <x-navbar :items="[]" />
        </div>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('js')
    <script type="module">
        
        // let dropdowns = document.querySelectorAll('.dropdown-toggle')
        // dropdowns.forEach((dd)=>{
        //     dd.addEventListener('click', function (e) {
        //         var el = this.nextElementSibling
        //         el.style.display = el.style.display==='block'?'none':'block'
        //     })
        // })

        // $(document).on('click','body *',function(){
        //     $('ul .dropdown-menu').css({ 'display' : 'none'});
        // });
                
    </script>
</body>
</html>
