<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" sizes="180x180" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/dhtml/theme_black.css') }}">
    @stack('myCss')
    <style>
        table.dataTable tbody td {
            vertical-align: middle;
            text-align: center;
        }

        table.dataTable thead th {
            vertical-align: middle;
            text-align: center;
        }

        /* .input-group-text {
            background: white;
        }

        .gantt_cal_light {
            display: none !important;
        }

        .gantt_cal_ltitle {
            display: none;
        }

        .gantt_cal_larea {
            display: none;
        }

        .gantt_cal_ltext {
            display: none;
        } */

        .status_line {
            background-color: #0ca30a;
        }
    </style>
</head>

<body class="sidebar-mini sidebar-collapse" style="height: 100vh">
    <div class="wrapper">
        @include('layouts.nav-top')
        @include('layouts.nav-left')
        <div class="content-wrapper">
            @yield('content')
        </div>

        {{-- <footer class="main-footer p-2 text-center border-0 shadow-sm">
            <small>{{ __('Developed By STI') }}</small>
        </footer> --}}
    </div>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script>
        let lang = $('html').attr('lang');
    </script>
    <script src="{{ asset('dist/js/rowgroup.js') }}"></script>
    <script src="{{ asset('plugins/dhtml/dhtmlgantt.js') }}"></script>
    <!-- AngularJS 1.6.9 -->
    <script src="{{ asset('js/languages/vi.js') }}"></script>
    <script src="{{ asset('js/languages/en.js') }}"></script>
    <script src="{{ asset('js/languages/ko.js') }}"></script>
    <script src="{{ asset('plugins/socket/socket.io.min.js') }}"></script>
    <!-- jQuery -->
    <script>
        let url = window.location.href;
        let cut = url.split('?')[0];
        let target = cut.split('/');
        $('aside .nav-link').removeClass('active');
        target.splice(0, 1);
        target.splice(0, 1);
        target.splice(0, 1);
        for (let i = 0; i < target.length; i++) {
            let myClass = target[i].split('#')[0];
            $('.' + myClass).addClass('active');
        }
        $('input').prop('autocomplete', 'off');
        var role_edit = {{ Auth::user()->checkRole('update_master') || Auth::user()->level == 9999 ? 1 : 0 }}
        var role_delete = {{ Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999 ? 1 : 0 }}
        // var ip_address = '192.168.1.3';
        // var socket_port = '3000';
        // var socket = io('http://' + ip_address + ':' + socket_port);
    </script>
    {{-- <script src="/socket.io/socket.io.js"></script> --}}
    <script>
        // var socket = io("{{ config('app.socket_host') }}{{ config('app.socket_port') }}");
    </script>
    @stack('scripts')
</body>

</html>
