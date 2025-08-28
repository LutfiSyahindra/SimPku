    <meta charset="utf-8" />
    <title>Layout Detached | Jidox - Material Design Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset("dist/assets/images/logoArsy.png") }}">

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="{{ asset("dist/assets/vendor/daterangepicker/daterangepicker.css") }}">

    <!-- Vector Map css -->
    <link rel="stylesheet"
        href="{{ asset("dist/assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css") }}">

    <!-- Theme Config Js -->
    <script src="{{ asset("dist/assets/js/config.js") }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App css -->
    <link href="{{ asset("dist/assets/css/app.min.css") }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset("dist/assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <!-- PWA manifest dan theme-color -->

    @vite(["resources/js/pwa.js"])
    <link rel="manifest" href="{{ asset("build/manifest.webmanifest") }}">
    <meta name="theme-color" content="#0d6efd">
