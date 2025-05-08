<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- <title>{{ config('app.name', 'GPIB Immanuel Malang') }}</title> --}}
    <title>GPIB Immanuel Malang</title>
    <link rel="shortcut icon" href="/images/logo-gpib-sm.webp">

    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Untuk mengirimkan token Laravel CRSF pada setiap request ajax-->

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    @stack('css')

    <style>
        #btnScrollTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            display: none; /* Awalnya disembunyikan */
            z-index: 999;
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
        }

    </style>
    

</head>
<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        
        <!-- Navbar -->
        @include('layouts.header')
        <!-- /.navbar -->
        
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar position-fixed h-100 sidebar-light-navy elevation-4">
            
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="brand-link">
                <img src="/images/logo-gpib-sm.webp" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
                <span class="brand-text "><b>GPIB IMM MLG</b></span>
            </a>
            
            <!-- Sidebar -->
            @include('layouts.sidebar')
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @include('layouts.breadcrumb')

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        @include('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    
    <!-- jQuery dan Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    
    <script>
        // Untuk mengirimkan token Laravel CSRF pada setiap request ajax
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    </script>
    
    @stack('js') <!-- Digunakan untuk memanggil custom js dari perintah push('js') pada masing-masing view -->
    
    <button id="btnScrollTop" class="btn btn-primary">
        {{-- ↑ --}}
        <i class="nav-icon fas fa-arrow-up"></i> 
    </button>

    <script>
        $(document).ready(function() {
            // Tampilkan tombol saat scroll ke bawah 300px
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#btnScrollTop').fadeIn();
                } else {
                    $('#btnScrollTop').fadeOut();
                }
            });
    
            // Saat tombol diklik, scroll ke atas dengan animasi
            $('#btnScrollTop').click(function() {
                $('html, body').animate({scrollTop: 0}, 500);
            });
        });
    </script>
</body>
</html>
