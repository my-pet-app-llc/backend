<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <title>Adminator - @yield('title')</title>
    <link rel="stylesheet" href="{{  mix('/css/admin.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

</head>
<body class="app">
    <div id='loader'>
        <div class="spinner"></div>
    </div>
    @guest
        @yield('login')
    @else
        <div>
            @include('layout.sideBar')
            <div class="page-container">
                @include('layout.topBar')
                <main class='main-content bgc-grey-100'>
                    <div id='mainContent'>
                        @yield('mainContent')
                        @yield('modals')
                    </div>
                </main>
            </div>
        </div>
    @endguest
    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            setTimeout(() => {
                loader.classList.add('fadeOut');
            }, 300);
        });
    </script>
    <script src="{{ mix('/js/admin.js') }}"></script>
    <script src="{{ mix('/js/updates.js') }}"></script>
    <script src="{{ mix('/js/helper.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/fileinput.min.js"></script>
</body>
</html>
