@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cvest2.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page cor-de-fundo')

@section('body')
    {{-- <div class="login-box"> --}}
        <div class="container ">
            <div class="row">
                <div class="col-md-5 col-xs-12" style="background-color: white; height: 100vh; align-items: center; display: flex;">
                    <div style="width: 100%">
                        <div class="login-logo">
                            <i class="fas fa-user-lock"></i>
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
                        </div>
                        <!-- /.login-logo -->
                        <div class="login-box-body">
                            <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
                            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        placeholder="{{ trans('adminlte::adminlte.email') }}">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="{{ trans('adminlte::adminlte.password') }}">
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if ($errors->has('ip'))
                                    <div class="alert alert-danger alert-dismissible">
                                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                                        <h4><i class="icon fas fa-ban"></i> Acesso negado!</h4>
                                        {{ $errors->first('ip') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="checkbox icheck">
                                            <label>
                                                <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
                                            </label>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-xs-4">
                                        <button type="submit"
                                                class="btn btn-primary btn-block btn-flat">{{ trans('adminlte::adminlte.sign_in') }}</button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </form>
                            <div class="auth-links">
                                <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
                                class="text-center"
                                >{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
                                <br>
                                @if (config('adminlte.register_url', 'register'))
                                    <a href="{{ url(config('adminlte.register_url', 'register')) }}"
                                    class="text-center"
                                    >{{ trans('adminlte::adminlte.register_a_new_membership') }}</a>
                                @endif
                            </div>
                        </div>
                        <!-- /.login-box-body -->
                    </div>
                </div>
            </div>
        </div>
    {{-- </div><!-- /.login-box --> --}}
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $('container').css('marginTop', Match.max(0,($(window).height() - $(this).height() / 2)) + 'px');
        });
    </script>
    @yield('js')
@stop
