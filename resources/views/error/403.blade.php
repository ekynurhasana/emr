<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 Error Page</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('/adminlte/dist/css/adminlte.min.css')}}">
    </head>
    <body class="hold-transition lockscreen">
        <div class="lockscreen-wrapper">
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-warning">403</h2>

                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! You do not have permission to access this page.</h3>

                        <p>
                            We could not find the page you were looking for.
                            Meanwhile, you may <a href="{{url('/')}}">return to dashboard</a> or call the administrator.
                        </p>
                    </div>
                </div>
            </section>
        </div>
        <script src="{{asset('/adminlte/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('/adminlte/dist/js/adminlte.min.js')}}"></script>
        <script src="{{asset('/adminlte/dist/js/demo.js')}}"></script>
    </body>
</html>
