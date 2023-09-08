<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield("title")</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('/adminlte/dist/css/adminlte.min.css')}}">
        <link rel="stylesheet" href="{{asset('/plugins/toastr/toastr.min.css')}}">
        <link rel="stylesheet" href="{{asset('/asset/css/emr.css')}}">
        <link rel="stylesheet" href="{{asset('/asset/css/sweetalert.css')}}">
    </head>
    <body class="hold-transition login-page">
        @yield("content")

        <!-- jQuery -->
        <script src="{{asset('/adminlte/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('/adminlte/dist/js/adminlte.min.js')}}"></script>
        <script src="{{asset('/plugins/sweetalert2/sweetalert2.js')}}"></script>
        <script src="{{asset('/plugins/toastr/toastr.js')}}"></script>
        <script src="{{asset('/asset/js/sweetalert.js')}}"></script>
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
        @yield("script")
    </body>
</html>
