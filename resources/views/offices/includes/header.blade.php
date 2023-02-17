<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title','Hiring')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- <!-- Bootstrap 3.3.7 --> --}}
    <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/AdminLTE-rtl.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/bootstrap-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/rtl.css') }}">

        <style>
            body,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: 'Cairo', sans-serif !important;
            }

            .col-print-1 {
                width: 8%;
                float: left;
            }

            .col-print-2 {
                width: 16%;
                float: left;
            }

            .col-print-3 {
                width: 25%;
                float: left;
            }

            .col-print-4 {
                width: 33%;
                float: left;
            }

            .col-print-5 {
                width: 42%;
                float: left;
            }

            .col-print-6 {
                width: 50%;
                float: left;
            }

            .col-print-7 {
                width: 58%;
                float: left;
            }

            .col-print-8 {
                width: 66%;
                float: left;
            }

            .col-print-9 {
                width: 75%;
                float: left;
            }

            .col-print-10 {
                width: 83%;
                float: left;
            }

            .col-print-11 {
                width: 92%;
                float: left;
            }

            .col-print-12 {
                width: 100%;
                float: left;
            }

            @media print {
                .no-print {
                    visibility: hidden;
                }
            }
        </style>
    @else
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/admin/theme2/css/AdminLTE.min.css') }}">
    @endif

    <style>
        .mr-2 {
            margin-right: 5px;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #367fa9;
            width: 60px;
            height: 60px;
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        label {
            cursor: pointer;
            /* Style as you please, it will become the visible UI component. */
        }
            
        #upload-photo {
            opacity: 0;
            position: absolute;
            z-index: -1;
        }

    </style>
    {{-- <!-- jQuery 3 --> --}}
    <script src="{{ asset('public/admin/theme2/js/jquery.min.js') }}"></script>

    {{-- noty --}}
    <link rel="stylesheet" href="{{ asset('public/admin/theme2/plugins/noty/noty.css') }}">
    <script src="{{ asset('public/admin/theme2/plugins/noty/noty.min.js') }}"></script>

    {{-- <!-- morris --> --}}
    <link rel="stylesheet" href="{{ asset('public/admin/theme2/plugins/morris/morris.css') }}">

    {{-- <!-- iCheck --> --}}
    <link rel="stylesheet" href="{{ asset('public/admin/theme2/plugins/icheck/all.css') }}">

    {{-- html in  ie --}}
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    @stack('style')
</head>

<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">