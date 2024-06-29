<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Inertia Ecommerce</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/css/price-range.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/sweetalert-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/public/css/style.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    @viteReactRefresh
    @vite('resources/js/app.jsx')
</head>
<body>
@inertia
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
<script src="{{asset('assets/public/js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" ></script>
<script src="{{asset('assets/js/price-range.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script src="{{asset('assets/js/action.js')}}"></script>
<!-- @yield('pageJsScripts') -->
</body>
</html>