<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <title>{{ __('admin.title.mypet_admin_panel') }}</title>
    <link rel="stylesheet" href="{{  mix('/css/index.css') }}">
    <link rel="stylesheet" href="{{  mix('/css/app.css') }}">
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/fileinput.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCNSsSHCDXZ3DgXfDGCSklTLNbrQNgpAZE&libraries=places"></script>
    <script defer src="{{mix('/js/app.js')}}"></script>
</body>
</html>
