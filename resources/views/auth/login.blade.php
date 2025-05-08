<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GPIB Immanuel Malang | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

  <style>
    body {
      background: url('/images/bg-login 3.webp') ;
      background-size: cover; /* Menyesuaikan gambar dengan layar */
    }

    .card {
      border-radius: 15px; /* Sesuaikan dengan tingkat kelengkungan yang diinginkan */
  overflow: hidden; /* Mencegah konten keluar dari border-radius */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Opsional: Tambahkan bayangan agar lebih elegan */
  background: rgba(255, 255, 255, 0.8) !important; /* Opacity 80% */
  backdrop-filter: blur(10px); /* Efek blur agar lebih menarik */
    }

    .logo-container {
      display: flex;
      align-items: center; /* Menjaga agar logo sejajar dengan teks */
      gap: 10px; /* Jarak antara logo dan teks */
    }

    .brand-image {
        width: 50px; /* Sesuaikan dengan ukuran logo */
        height: auto;
        align-items: center;
    }

    .gpib {
        font-size: 24px; /* Sesuaikan dengan tinggi logo */
        font-weight: bold;
        margin-bottom: 2px;
    }

    .immanuel {
      margin-top: 0;
        font-size: 20px;
    }

    @font-face {
        font-family: 'Helvetica Custom';
        src: url('/fonts/helvetica/Helvetica Condensed Oblique.otf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    .brand-text {
        font-family: 'Helvetica Custom', sans-serif;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        align-items: flex-start; /* Rata kiri */
        text-align: left; /* Pastikan teks juga rata kiri */
        margin-left: 20px;
    }

  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <div class="input-group mt-4 mb-4" style="justify-content: center">
        <a href="{{ route('login.authenticate') }}">
          <img src="/images/logo-gpib-sm.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
        </a>
        <div class="brand-text">
          <h4 class="gpib text-secondary">GPIB</h4>
          <h5 class="immanuel text-secondary">Immanuel Malang</h5>
        </div>      
      </div>
      <hr>
      <p class="login-box-msg mb-2 mt-4">Silahkan login untuk masuk sistem</p>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if (session('success'))
        <div class="aler alert-success">
          <ul>
            <li>{{session('success')}}</li>
          </ul>
        </div>
      @endif

      <form action="{{ route('login.authenticate') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4 mx-auto mt-4 mb-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>

  <div class="text-center mt-3">
    <a href="/" class="btn btn-dark">
      <i class="fas fa-home"></i> Kembali ke Beranda
    </a>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
