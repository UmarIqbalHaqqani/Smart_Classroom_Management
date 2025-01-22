<!DOCTYPE html>
<html lang="en">

<head>
    <!-- All Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>@lang('auth.new_password')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/fontawesome.all.min.css') }}">

    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/style.css') }}">
    <style>
        .text-danger.text-left {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- <a href="" rel=”nofollow”></a> -->

    <section class="login">
        <div class="login_wrapper">
            <!-- login form start -->
            <div class="login_wrapper_login_content">
                <div class="login_wrapper_logo text-center"><img src="{{ asset(generalSetting()->logo) }}"
                        alt=""></div>
                <div class="login_wrapper_content">
                    <h4>@lang('auth.reset_password')</h4>
                    <form action="{{ route('storeNewPassword') }}" method='POST'>
                        <input type="hidden" name="email" value="{{ $email }}">
                        @csrf
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-lock-alt"></i></label>
                            <input type="password" name='new_password' class="input-control-input"
                                placeholder='@lang('auth.enter_new_password')'
                                {{-- required --}}>
                            @if ($errors->has('new_password'))
                                <span class="text-danger text-left pl-3" role="alert">
                                    {{ $errors->first('new_password') }}
                                </span>
                            @endif
                        </div>
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-lock-alt"></i></label>
                            <input type="password"name='confirm_password' class="input-control-input"
                                placeholder='@lang('auth.confirm_new_password')'
                                {{-- required --}}>
                            @if ($errors->has('confirm_password'))
                                <span class="text-danger text-left pl-3" role="alert">
                                    {{ $errors->first('confirm_password') }}
                                </span>
                            @endif
                        </div>
                        <div class="input-control">
                            <input type="submit" class='input-control-input' value="Submit">
                        </div>
                    </form>
                </div>
            </div>
            <!-- login form end -->
        </div>
    </section>


    <!-- jQuery JS -->
    <script src="{{ asset('public/theme/edulia/js/jquery.min.js') }}"></script>

    <!-- Main Script JS -->
    <script src="{{ asset('public/theme/edulia/js/script.js') }}"></script>
</body>

</html>
