<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Page Builder') . ' | '.$page->name }}</title>
    @if( ! is_null(schoolConfig() ))
        <link rel="icon" href="{{asset(schoolConfig()->favicon)}}" type="image/png"/>
    @else
        <link rel="icon" href="{{asset('public/uploads/settings/favicon.png')}}" type="image/png"/>
    @endif
    @if( config('pagebuilder.add_bootstrap') === 'yes' )
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/bootstrap.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/feather-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/jquery-confirm.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/jquery.colorpicker.bygiro.css')}}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/summernote-lite.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/nouislider.min.css')}}">
    
    <link rel="stylesheet" href="{{ asset('public/vendor/pagebuilder/css/larabuild-pagebuilder.css')}}">
    <link rel="stylesheet" href="{{ asset('public/vendor/pagebuilder/css/larabuild-pagebuilder.css') }}">

    {{-- Icon Picker --}}
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/iconPicker.css')}}">
    <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/fontawesome.all.min.css') }}">

    <link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/css/style.css')}}"> 
    <link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/packages/zeynep/zeynep.min.css')}}"> 
    <link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/themify/themify-icons.min.css')}}"> 
    <link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/css/pagebuilder_custom.css')}}"> 
</head>

<body>
    @yield('builder-content')

    @if( config('pagebuilder.add_bootstrap') === 'yes' )
    <script defer src="{{ asset('public/vendor/optionbuilder/js/bootstrap.min.js') }}"></script>
    @endif

    <script src="{{ asset('public/vendor/optionbuilder/js/jquery.min.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/select2.min.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/jquery-confirm.min.js')}}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/popper-core.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/tippy.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/flatpickr.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/jquery.colorpicker.bygiro.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/summernote-lite.min.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/nouislider.min.js') }}"></script>
    <script defer src="{{ asset('public/vendor/optionbuilder/js/optionbuilder.js') }}"></script>
    <script defer src="{{ asset('public/vendor/pagebuilder/js/Sortable.min.js') }}"></script>

    {{-- Icon Picker --}}
    <script defer src="{{ asset('public/vendor/optionbuilder/js/iconPicker.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.pb-addsection-info a i.icon-plus', function(e){
                e.stopPropagation();
                $('#elements-btn').trigger('click');
            })

            // Icon Picker
            $(document).on('focus', '.icon_picker', function(){
                $(this).iconpicker({animation:true});
                $(this).next('.iconpicker-popover').addClass('show');
            });
        })
    </script>
    @stack(config('pagebuilder.site_script_var'))

    @stack('builder-js')
    @yield('builder-templates')
</body>

</html>