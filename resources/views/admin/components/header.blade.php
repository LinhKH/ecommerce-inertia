<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | {{site_settings()->site_name}}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="site-url" content="{{ url('/') }}">
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/css/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('assets/css/icheck-bootstrap.min.css')}}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/sweetalert-bootstrap-4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/summernote-bs4.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="{{asset('assets/css/image-uploader.css')}}">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!-- Style.CSS -->
  <!-- <link rel="stylesheet" href="{{asset('assets/css/style.css')}}"> -->
  <link rel="stylesheet" href="{{asset('assets/css/Taginput.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/tokenfield.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}">
  <style>
    .page-checkbox{
      display: inline-block;
      position: relative;
    }
    .page-checkbox input[type=checkbox]{
        margin: 0;
        visibility: hidden;
        position: absolute;
        left: 1px;
        top: 1px;
    }
    .page-checkbox label{
        width: 35px;
        height: 22px;
        margin: 0;
        border: 3px solid #555;
        border-radius: 100px;
        cursor: pointer;
        display: block;
        overflow: hidden;
        position: relative;
        transition: all 0.75s ease;
    }
    .page-checkbox label:before,
    .page-checkbox label:after{
        content: '';
        background: #555;
        border-radius: 50px;
        width: 14px;
        height: 14px;
        position: absolute;
        top: 1px;
        left: 2px;
        opacity: 1;
        transition: 0.75s ease;
    }
    .page-checkbox label:after{
        left: auto;
        right: 2px;
        opacity: 0;
    }
    .page-checkbox input[type=checkbox]:checked+label{
        border-color: #a30d9e;
        box-shadow: 0 0 5px rgba(163,13,158,0.4);
    }
    .page-checkbox input[type=checkbox]:checked+label:before{ opacity: 0; }
    .page-checkbox input[type=checkbox]:checked+label:after{
        background-color: #a30d9e;
        opacity: 1;
    }
    @media only screen and (max-width:767px){
        .page-checkbox{ margin: 0 0 20px; }
    }

    .checkbox{
    margin: 0;
    display: inline-block;
    position: absolute;
    top: -17px;
}
.checkbox input[type=checkbox]{
    margin: 0;
    visibility: hidden;
    left: 1px;
    top: 1px;
}
.checkbox label{
    background: #bbb;
    width: 36px;
    height: 14px;
    min-height: auto;
    padding: 0;
    cursor: pointer;
    border-radius: 50px;
    display: block;
    position: relative;
    z-index: 1;
    transition: all 0.4s ease 0s;
}
.checkbox label:before{
    content: '';
    background: #fff;
    width: 20px;
    height: 20px;
    border-radius: 40px;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2),
                0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 0 0 16px rgba(0, 108, 181, 0);
    transform: translateY(-50%);
    position: absolute;
    top: 50%;
    left: -2px;
    transition: all 0.26s ease 0s;
}
/* .checkbox label:active:before{
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2),
                0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 0 0 16px rgba(255,20,231, 0.6);
} */
.checkbox input[type=checkbox]:checked+label{
    background: #e8ebf1;
    transition: all 0.25s ease 0s;
}
.checkbox input[type=checkbox]:checked+label:before{
    background: #0abb75;
    left: 18px;
}

@media only screen and (max-width:767px){
    .checkbox{ margin: 0 0 20px; }
}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    </ul>
    <ul class="navbar-nav">
      <div class="dropdown">
          <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Admin
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="{{url('admin/profile-settings')}}">My Profile</a>
              <a class="dropdown-item logout admin-logout" href="#">Log Out</a>
          </div>
      </div>
    </ul>
  </nav>
  <!-- /.navbar -->
