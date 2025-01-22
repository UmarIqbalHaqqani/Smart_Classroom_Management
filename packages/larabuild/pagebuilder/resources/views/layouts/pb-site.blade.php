<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if( !empty($page->title) )
            <title>{{ $page->title }} </title>
        @endif

        @if ( !empty($page->description) )
            <meta name="description" content="{{ $page->description }}" />
        @endif

        @if( config('pagebuilder.add_bootstrap') === 'yes' )
            <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/bootstrap.min.css') }}">
        @endif

        <link rel="stylesheet" href="{{ asset('demo/css/demo.css') }}">
        
        @stack(config('pagebuilder.style_var'))

    </head>
    <body>

        <main class="mainbag">
            @yield(config('pagebuilder.site_section'))
        </main>

        @if( config('pagebuilder.add_jquery') === 'yes' )
            <script src="{{ asset('public/vendor/optionbuilder/js/jquery.min.js') }}"></script>
        @endif

        @if( config('pagebuilder.add_bootstrap') === 'yes' )
            <script defer src="{{ asset('public/vendor/optionbuilder/js/bootstrap.min.js') }}"></script>
        @endif
         
        @stack(config('pagebuilder.script_var'))  
    </body>
</html>
