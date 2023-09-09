@extends('auth.template')
@section('title')
    Login
@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Fatima</b> Medical Record</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Welcome to Fatima Medical Record, please login first!</p>

            @if (session('errors'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form action="#" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email" name="email" value="{{ old('email') }}" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        {{-- <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div> --}}
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-info btn-block" id="signin_button">Sign In</button>
                    </div>
                </div>
            </form>

            {{-- <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div> --}}

            {{-- <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p> --}}
            {{-- <p class="mb-0">
                <a href="register" class="text-center">Register</a>
            </p> --}}
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        // create script for button sign in move if hover when form has not been filled
        $(function(){
            if($("input[name='email']").val() == "" || $("input[name='password']").val() == ""){
                $("#signin_button").attr('disabled', true);
            }
            $("input[name='email'], input[name='password']").keyup(function(){
                if($("input[name='email']").val() != "" && $("input[name='password']").val() != ""){
                    $("#signin_button").attr('disabled', false);
                }else{
                    $("#signin_button").attr('disabled', true);
                }
            });
        });
        $(function(){
            $("#signin_button").click(function(){
                $(this).html('<i class="fas fa-spinner fa-spin"></i>');
                $(this).attr('disabled', true);
                $(this).closest('form').submit();
            });
        });
    </script>
@endsection
