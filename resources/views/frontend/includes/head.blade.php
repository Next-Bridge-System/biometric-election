<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal - Punjab Bar Council</title>
    <link rel="shortcut icon" href="{{asset('public/admin/images/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('public/admin/images/favicon.ico')}}" type="image/x-icon">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('public/admin/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
        href="{{asset('public/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Bootstrap Toggel -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg=="
        crossorigin="anonymous" />

    <link href="{{asset('public/admin/plugins/filepond/filepond.css')}}" rel="stylesheet">
    <script src="{{asset('public/admin/plugins/filepond/filepond.js')}}"></script>

    <!-- Custom CSS -->
    <link rel=" stylesheet" href="{{asset('public/admin/custom.css')}}">
    <link rel=" stylesheet" href="{{asset('public/css/livewire-loader.css')}}">

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .hidden {
            display: none !important;
        }


        .custom-image-upload {
            padding: 3px;
            background-color: white;
        }

        .custom-image-preview {
            width: 150px;
            border-radius: 5px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .custom-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            z-index: 999;
            opacity: 0.9;
        }
    </style>

    @yield('styles')
    @livewireStyles
</head>