<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign In | {{ env('APP_NAME') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/font-awesome/css/font-awesome.min.css') }}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/Ionicons/css/ionicons.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/AdminLTE.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  </head>

  <body class="hold-transition login-page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="login-box-body border-round box-shadow">
        <div class="login-logo text-center">
          <img src="{{ asset('images/logo.png') }}" alt="">
        </div>

        @include('backend.alert')
        <form action="{{ route('login') }}" method="POST">
          {{ csrf_field() }}

          <div class="form-group has-feedback">
            <input type="email" class="form-control border-round" name="email" placeholder="Email" value="{!! old('email') !!}" required autofocus>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>

          <div class="form-group has-feedback">
            <input type="password" class="form-control border-round" name="password" id="password" placeholder="Password">
            <i class="glyphicon glyphicon-eye-open form-control-feedback"></i>
          </div>

          <div class="row">
            <!-- /.col -->
            <div class="col-sm-12">
              <button type="submit" class="btn btn-blue btn-block btn-round box-shadow">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

      </div>

      <p class="text-center mt-2">
        <a class="txt-blue" href="{{ route('home') }}" style="font-size:14px;">
          <i class="fa fa-long-arrow-left"></i> Back to Home 
        </a>
      </p>

    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="{{ asset('backend/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

  </body>
</html>