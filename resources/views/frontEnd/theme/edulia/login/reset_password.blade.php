<!DOCTYPE html>
<html lang="en">

<head>
    <!-- All Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>@lang('auth.reset_password')</title>
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

    <section class="login">
        <div class="login_wrapper">
            <!-- login form start -->
            <div class="login_wrapper_login_content">
                <div class="login_wrapper_logo text-center"><img src="{{ asset(generalSetting()->logo) }}"
                        alt=""></div>
                <div class="login_wrapper_content">
                    <h4>@lang('auth.reset_password')</h4>
                    <form action="{{ route('email/verify') }}" method='POST'>
                        @csrf
                        <div class="input-control">
                            <label for="#" class="input-control-icon"><i class="fal fa-envelope"></i></label>
                            <input type="email" name='email' class="input-control-input"
                                placeholder='@lang('auth.enter_email_address')'
                                {{-- required --}}>
                            @if ($errors->has('email'))
                                <span class="text-danger text-left pl-3" role="alert">
                                    {{ $errors->first('email') }}
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
