
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{site_settings()->site_title}} | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/css/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/css/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/sweetalert-bootstrap-4.min.css')}}">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    @if(site_settings()->site_logo != '')
    <img src="{{asset('site/'.site_settings()->site_logo)}}" alt="{{site_settings()->site_name}}" width="150px">
    @else
    <h3>{{site_settings()->site_name}}</h3>
    @endif
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <form id="adminLogin"  method="POST">
        @csrf
        <input type="hidden" class="url" value="{{url('/')}}">  
        <div class="form-group mb-3">
          <input type="text" class="form-control username" name="username" placeholder="Username" required>
        </div>
        <div class="form-group mb-3">
          <input type="password" class="form-control password" name="password" placeholder="Password" required>
        </div>
        <div class="row">
          <div class="offset-md-8 col-4">
            <input type="submit" class="btn btn-primary float-right" name="login" value="Login">
          </div>
          <div class="col-md-12">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(Session::has('loginError'))
                    <div class="alert alert-danger">
                        {{Session::get('loginError')}}
                    </div>
                @endif
            </div>
          </div>
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/js/adminlte.min.js')}}"></script>
<script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/additional-methods.min.js')}}"></script>
<script src="{{asset('assets/js/admin-login.js')}}"></script>
</body>
</html>
