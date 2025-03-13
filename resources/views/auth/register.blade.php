@extends('layouts.registration')

@section('register')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url('/') }}"><b>Project</b> POS</a>
            <img src="{{ asset('images/logo.png') }}" alt="logo.png" width="50">
        </div>
        <!-- /.register-logo -->
        <div class="register-box-body">
            <p class="login-box-msg">Register a new membership</p>


            <form action="{{ route('register') }}" method="post">
                @csrf
                <div class="form-group has-feedback @error('name') has-error @enderror">
                    <input type="name" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}"
                        required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                        <span class="help-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group has-feedback @error('email') has-error @enderror">
                    <input type="email" name="email" class="form-control" placeholder="Email"
                        value="{{ old('email') }}" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                        <span class="help-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group has-feedback @error('password') has-error @enderror">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @error('password')
                        <span class="help-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password"
                        required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <a href="{{ route('login') }}">Sudah Terdaftar? Silahkan Log In</a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>

                </div>

            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
@endsection
