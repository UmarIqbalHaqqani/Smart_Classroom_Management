@php
    $gs = generalSetting();
@endphp
<!DOCTYPE html>
@php
    App::setLocale(getUserLanguage());
    $ttl_rtl = userRtlLtl();

    $login_background = App\SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

    if (empty($login_background)) {
        $css = 'background: url(' . url('public/backEnd/img/edulia-login-bg.jpg') . ') no-repeat center; background-size: cover; ';
    } else {
        if (!empty($login_background->image)) {
            $css = "background: url('" . url($login_background->image) . "') no-repeat center; background-size: cover;";
        } else {
            $css = 'background:' . $login_background->color;
        }
    }
@endphp
<html lang="{{ app()->getLocale() }}" @if (isset($ttl_rtl) && $ttl_rtl == 1) dir="rtl" class="rtl" @endif>

<head>
    <!-- All Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>@lang('auth.login')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />

    <!-- Fonts -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/fontawesome.all.min.css') }}">

    @if(userRtlLtl() ==1)
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/style.css') }}">
    @endif
        <style>
        .row_gap_24 {
            row-gap: 24px;
        }

        .login{
            height: auto;
            min-height: 100vh;
            padding: 50px 0px;
        }

        .row_gap_24 input.input-control-input {
            font-size: 12px;
        }

        .login_wrapper {
            width: 550px;
            background: #fff;
            padding: 30px;
        }

        .text-danger.text-left {
            font-size: 14px;
        }
        .row.row-gap-10 {
            row-gap: 10px;
            --bs-gutter-x: 10px;
        }

        .row.row-gap-10 .input-control-input{
            padding: 10px;
        }

        .row.row-gap-10 [class*=col-]{
            --bs-gutter-x: 10px;
        }

        .row-gap-10 input.input-control-input {
            font-size: 11px;
        }
        @media only screen and (max-width: 767px){
                section.login {
                    place-content: center;
                    align-content: center;
                    justify-content: center;
            }
        }
        @media (max-width: 480px){
            .row.row-gap-10 [class*=col-]{
                width: 50%;
                max-width: 50%;
            }
        }
    </style>
</head>

<body>

    <section class="login" style="{{ $css }}">
        <div class="login_wrapper">

            {{--@if (config('app.app_sync') && isset($schools) && session('domain') == 'school')

                <div class="row justify-content-center">
                    @foreach ($schools as $school)
                        <div class="col-md-3">
                            <h4 class="text-center text-danger">@lang('auth.school') {{ $loop->iteration }}</h4>
                            <hr>
                            <a target="_blank" href="//{{ $school->domain . '.' . config('app.short_url') }}/home"
                                class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12">{{ Str::limit($school->school_name, 20, '...') }}</a>
                        </div>
                    @endforeach
                </div>
            @endif--}}

            <!-- login form start -->
            <div class="login_wrapper_login_content">
                <div class="login_wrapper_logo text-center"><img src="{{ asset(generalSetting()->logo) }}"
                        alt=""></div>
                <div class="login_wrapper_content">
                    <h4>@lang('auth.login_details')</h4>
                    <form action="{{ route('login') }}" method='POST'>
                        @csrf
                        <input type="hidden" name="username" id="username-hidden">
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-envelope"></i></label>
                            <input type="text" name="email" class="input-control-input"
                                placeholder="@lang('auth.enter_email_address')" value="{{ old('email') }}">
                        </div>
                        @if ($errors->has('email'))
                            <span class="text-danger text-left mb-15" role="alert">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-lock-alt"></i></label>
                            <input type="password" name='password' class="input-control-input"
                                placeholder='@lang('auth.enter_password')'>
                        </div>
                        @if ($errors->has('password'))
                            <span class="text-danger text-left mb-15" role="alert">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                        <div class="input-control d-flex flex-wrap row_gap_24">
                            <label for="#" class="checkbox">
                                <input type="checkbox" class="checkbox-input" name="remember" id="rememberMe"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span class="checkbox-title">@lang('auth.remember_me')</span>
                            </label>
                            <a href="{{ route('recoveryPassord') }}" id='forget'>@lang('auth.forget_password')?</a>
                        </div>
                        <div class="input-control">
                            <input type="submit" class='input-control-input' value="Sign In">
                        </div>
                    </form>
                </div>
            </div>

            @if (config('app.app_sync') && session('domain') != 'school')
                <div class="row justify-content-center align-items-center" style="">
                    <div class="col-lg-6 col-md-8">
                        <div class="grid__button__layout">
                            @foreach ($users as $user)
                                @if ($user)
                                    <form method="POST" class="loginForm" action="{{ route('login') }}">
                                        @csrf()
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <input type="hidden" name="auto_login" value="true">
                                        {{-- <button type="submit"
                                class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12 text-nowrap">{{ $user[0]->roles->name }}</button> --}}
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <!-- login form end -->
            @if (config('app.app_sync') && session('domain') == 'school')
                <div class="row justify-content-center align-items-center mt-20">
                    <div class="col-lg-12">
                        <div class="row row-gap-10">
                            @foreach ($users as $user)
                                @if ($user)
                                    <div class="col-4 col-sm-4 col-md-3">
                                        <form method="POST" class="loginForm" action="{{ route('login') }}">
                                            @csrf()
                                            <input type="hidden" name="email" value="{{ $user->email }}">
                                            <input type="hidden" name="auto_login" value="true">
                                            <input type="submit" class='input-control-input'
                                                value="{{ $user->roles->name }}">
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if (config('app.app_sync') && isset($schools) && session('domain') == 'school')
            <!-- Star School Links -->
            <div class="saas_school_top_five_link_show mt-4">
                <div class="row row-gap-10 justify-content-center">
                    <div class="col-12">
                        <h6 class="text-center title">{{ __('edulia.schools') }}</h6>
                    </div>
                    @foreach ($schools as $school)
                    <div class="col-4 col-sm-4 col-md-3">
                        <a target="_blank" href="//{{ $school->domain . '.' . config('app.short_url') }}/home" class="link_to_school">{{ Str::limit($school->school_name, 20, '...') }}</a>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- End School Links -->
            @endif
        </div>

    </section>


    <!-- jQuery JS -->
    <script src="{{ asset('public/theme/edulia/js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr JavaScript file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Main Script JS -->
    <script src="{{ asset('public/theme/edulia/js/script.js') }}"></script>
    <script src="{{ asset('public/backEnd/') }}/js/login.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#email-address").keyup(function() {
                $("#username-hidden").val($(this).val());
            });
        });
    </script>
     <script>
        @if(Session::has('toast_message'))
            toastr.{{ Session::get('toast_message')['type'] }}('{{ Session::get('toast_message')['message'] }}');
        @endif
    </script>
</body>

</html>
