<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$title}}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
        <!-- select2 -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('/adminlte/dist/css/adminlte.min.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        {{-- <link rel="stylesheet" href="{{asset('/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}"> --}}
        {{-- <link rel="stylesheet" href="{{asset('/adminlte/plugins/sweetalert2/sweetalert2.min.css')}}"> --}}
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/toastr/toastr.min.css')}}">
        <link rel="stylesheet" href="{{asset('/asset/css/emr.css')}}">
        <link rel="stylesheet" href="{{asset('/asset/css/sweetalert.css')}}">
        @yield('style')
    </head>
    @yield('style_custom')
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <!-- Preloader -->
            <!-- <div class="preloader flex-column justify-content-center align-items-center">
            {{-- <img class="animation__shake" src="{{asset('/adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60"> --}}
            </div> -->

            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="/" class="nav-link">Dashboard</a>
                    </li>
                    <!-- <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li> -->
                </ul>

                <ul class="navbar-nav ml-auto">
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-envelope mr-2"></i> 4 new messages
                                <span class="float-right text-muted text-sm">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-users mr-2"></i> 8 friend requests
                                <span class="float-right text-muted text-sm">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file mr-2"></i> 3 new reports
                                <span class="float-right text-muted text-sm">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </a>
                    </li>
                    {{-- profile --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <img src="{{asset('/storage/foto_profil/'.Session::get('detail_user')->file_foto)}}" class="img-circle elevation-2" alt="User Image" style="width: 30px; height: 30px;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">{{isset(Auth::user()->name) ? Auth::user()->name : ''}}</span>
                            <div class="dropdown-divider"></div>
                            <a href="/users/detail/{{Auth::user()->id}}" class="dropdown-item">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{url('/logout')}}" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                            <i class="fas fa-th-large"></i>
                        </a>
                    </li> --}}
                </ul>
            </nav>

            <aside class="main-sidebar sidebar-light-teal elevation-4">
                <a href="index3.html" class="brand-link">
                    <img src="{{asset('/asset/img/emr.png')}}" alt="Logo" class="brand-image" style="opacity: .8; margin-top: 2px;">
                    <span class="brand-text font-weight-light">EMR BELA</span>
                </a>

                <div class="sidebar">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{asset('/storage/foto_profil/'.Session::get('detail_user')->file_foto)}}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">{{Session::get('detail_user')->nama_lengkap}}</a>
                        </div>
                    </div>

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            {{-- <li class="nav-item menu-open">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./index.html" class="nav-link active">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Dashboard v1</p>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            {{-- return from appserviceprovider --}}
                            @if (isset($menu))
                                @foreach ($menu as $item)
                                    @if (isset($item->sub_menu))
                                        <li class="nav-header emr-sub-menu" id="li-{{$item->slug}}">
                                            {{$item->name}}
                                        </li>
                                        @foreach ($item->sub_menu as $sub_menu)
                                            <li class="nav-item">
                                                <a href="{{url($sub_menu->url)}}" class="nav-link" id="{{$sub_menu->slug}}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{$sub_menu->name}}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="nav-item">
                                            <a href="{{url($item->url)}}" class="nav-link" id="{{$item->slug}}">
                                                <i class="nav-icon fas fa-th"></i>
                                                <p>
                                                    {{$item->name}}
                                                </p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">{{$title}}</h1>
                            </div>
                            {{-- <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><button onclick="history.back()">Kembali</button></li>
                                </ol>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>
            </div>

            <footer class="main-footer">
                <strong>Copyright &copy; 2023 <a href="#">EMR BELA</a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b> 1.1.0
                </div>
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-light">
            <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>


        <!-- jQuery -->
        <script src="{{asset('/adminlte/plugins/jquery/jquery.min.js')}}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{asset('/adminlte/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{asset('/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        {{-- <script src="{{asset('/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script> --}}
        {{-- <script src="{{asset('/adminlte/plugins/sweetalert2/sweetalert2.js')}}"></script> --}}
        <script src="{{asset('/adminlte/plugins/toastr/toastr.js')}}"></script>
        <script src="{{asset('/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
        <script src="{{asset('/adminlte/dist/js/adminlte.js')}}"></script>
        <script src="{{asset('/adminlte/dist/js/pages/dashboard.js')}}"></script>
        <script src="{{asset('/asset/js/sweetalert.js')}}"></script>
        <script>
            $(document).ready(function(){
                var menu_slug = "{{isset($menu_slug) ? $menu_slug : ''}}";
                var sub_menu_slug = "{{isset($sub_menu_slug) ? $sub_menu_slug : ''}}";
                if(menu_slug != ""){
                    $("#li-"+menu_slug).addClass("menu-open");
                    $("#"+menu_slug).addClass("active");
                }
                if(sub_menu_slug != ""){
                    $("#"+sub_menu_slug).addClass("active");
                }
            });

            function hanyaAngka(event) {
                var angka = (event.which) ? event.which : event.keyCode
                if ((angka < 48 || angka > 57))
                    return false;
                return true;
            }

            function hanyaHuruf(event) {
                var huruf = (event.which) ? event.which : event.keyCode
                if ((huruf < 65 || huruf > 90) && (huruf < 97 || huruf > 122) && huruf > 32)
                    return false;
                return true;
            }


            function formatRupiah(angka){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return rupiah;
		}

            $(function() {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Silahkan pilih',
                    allowClear: true
                });
            })
        </script>

        @if (session('success'))
        <script>
            $(function(){
                var Toast = Swal.mixin({
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(document).ready(function(){
                    Toast.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{session("success")}}'
                    })
                });
            });
        </script>
        @endif
        @if (session('warning'))
        <script>
            $(function(){
                var Toast = Swal.mixin({
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(document).ready(function(){
                    Toast.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: '{{session("warning")}}'
                    })
                });
            });
        </script>
        @endif
        @if (session('error'))
        <script>
            $(function(){
                var Toast = Swal.mixin({
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(document).ready(function(){
                    Toast.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{session("error")}}'
                    })
                });
            });
        </script>
        @endif
        @if (session('errors'))
        <script>
            $(function(){
                var Toast = Swal.mixin({
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(document).ready(function(){
                    Toast.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Data tidak valid, periksa kembali data yang anda masukkan'
                    })
                });
            });
        </script>
        @endif

        @yield('script')
    </body>
</html>
