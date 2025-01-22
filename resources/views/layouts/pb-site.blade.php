<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
       
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if( ! is_null(schoolConfig() ))
            <link rel="icon" href="{{asset(schoolConfig()->favicon)}}" type="image/png"/>
        @else
            <link rel="icon" href="{{asset('public/uploads/settings/favicon.png')}}" type="image/png"/>
        @endif
        @if( !empty($page->title) )
            <title>{{ $page->title }} </title>
        @endif

        @if (!empty($page->description) )
            <meta name="description" content="{{ $page->description }}" />
        @endif

        @if( config('pagebuilder.add_bootstrap') === 'yes' )
            <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/bootstrap.min.css') }}">
        @endif

        {{-- <link rel="stylesheet" href="{{ asset('public/demo/css/demo.css') }}"> --}}
        
        <!-- Main css -->
        <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/fontawesome.all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/dataTables.jqueryui.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/responsive.jqueryui.min.css') }}">
        <link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/css/style.css')}}">
        <link rel="stylesheet" href="{{ asset('public/whatsapp-support/style.css') }}">

        @stack(config('pagebuilder.style_var'))

    </head>
    <body>
        <div class="bg-shade"></div>

        <main class="mainbag">
            {{-- @include('themes.edulia.pagebuilder.header-content.view') --}}
            @yield(config('pagebuilder.site_section'))
        </main>

        
        <!-- background overlay -->
            <div class="bg-shade"></div>
        <!-- background overlay -->

        @if( config('pagebuilder.add_jquery') === 'yes' )
            <script src="{{ asset('public/vendor/optionbuilder/js/jquery.min.js') }}"></script>
        @endif

        @if( config('pagebuilder.add_bootstrap') === 'yes' )
            <script defer src="{{ asset('public/vendor/optionbuilder/js/bootstrap.min.js') }}"></script>
        @endif
        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                var currentYear = new Date().getFullYear();
                document.body.innerHTML = document.body.innerHTML.replace(/2021 Edulia./g, currentYear + " Edulia.");
            });
        </script> --}}
        
        <script>
            window._locale = '{{ app()->getLocale() }}';
            window._rtl = {{ userRtlLtl() == 1 ? 'true' : 'false' }};
        </script>
        <script src="{{asset('public/theme/'.activeTheme().'/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('public/theme/'.activeTheme().'/js/dataTables.responsive.min.js')}}"></script>
        <script>
            $('body').append('<!--back to top btn--><a href="#" class="backtop"><i class="far fa-long-arrow-alt-up"</i>');
            $(window).on('scroll', function() {
                var x = $(window).scrollTop();
                if (x > 700) {
                    $('.backtop').addClass('show')
                } else {
                    $('.backtop').removeClass('show')
                }
            });
            
            $(".common_data_table table").DataTable({
                responsive: true,
                stripeClasses:[],
                language: {
                    searchPlaceholder: "Search ...",
                    search: "<i class='far fa-search datatable-search'></i>",
                },
            });

            $(window).on('scroll', function() {
                var x = $(window).scrollTop();
                if (x > 500) {
                    $('.heading_main').addClass('fixed-nav');
                } else {
                    $('.heading_main').removeClass('fixed-nav')

                }
            })
        </script>

        <script src="{{ asset('public/whatsapp-support/scripts.js') }}"></script>
        @stack(config('pagebuilder.script_var'))

        <style>
            .messengerContainer:hover {
                cursor: pointer;
            }
        </style>
        @if($messenger_position == 'right') 
            <style>
                .messengerContainer {
                    position: fixed;
                    bottom: 85px;
                    right: 22px;
                    width: 48px;
                    height: 45px;
                    border-radius: 50%;
                    color: white;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-size: 24px;
                    z-index: 3;
                    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
                }
            </style>
        @elseif($messenger_position == 'left') 
            <style>
                .messengerContainer {
                    position: fixed;
                    bottom: 85px;
                    width: 48px;
                    height: 45px;
                    left: 22px;
                    border-radius: 50%;
                    color: white;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-size: 24px;
                    z-index: 3;
                    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
                }
            </style>
        @endif

        @php
            $school_id = @Auth::user()->school_id;
            $tawk_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'tawk')->first()->is_enable;
            $messenger_is_enable = @App\Models\Plugin::where('school_id', $school_id)->where('name', 'messenger')->first()->is_enable;
        @endphp

        @if ($tawk_is_enable == 1)
            <div class="tawk-min-container tawk-test">
                <script type="text/javascript">
                    var Tawk_API=Tawk_API||{},
                    Tawk_LoadStart=new Date();
                    (function(){ var s1=document.createElement("script"),
                    s0=document.getElementsByTagName("script")[0];
                    s1.async=true; s1.src=`https://embed.tawk.to/@include('plugins.tawk_to')`;
                    s1.charset='UTF-8'; s1.setAttribute('crossorigin','*');
                    s0.parentNode.insertBefore(s1,s0); })();
                </script>
            </div>
        @endif
        
        @if ($messenger_is_enable == 1)
            <div class="messengerContainer">
                <!-- Messenger Chat Plugin Code -->
                <div id="fb-root"></div>
            
                <!-- Your Chat Plugin code -->
                <div id="fb-customer-chat" class="fb-customerchat">
                </div>
            
                <script>
                var chatbox = document.getElementById('fb-customer-chat');
                chatbox.setAttribute("page_id", "@include('plugins.messenger')");
                chatbox.setAttribute("attribution", "biz_inbox");
                </script>
            
                <!-- Your SDK code -->
                <script>
                window.fbAsyncInit = function() {
                    FB.init({
                    xfbml            : true,
                    version          : 'v18.0'
                    });
                };
            
                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
                </script>
            </div>
        @endif
        <script>
            var position = '{{ $position }}';
            var tawkposition = '';
        
            if(position == 'left'){
                tawkposition = 'bl';
            }else{
                tawkposition = 'br';
            }
            var Tawk_API = Tawk_API || {};
        
            Tawk_API.customStyle = {
                visibility : {
                    desktop : {
                        position : tawkposition,
                        xOffset : 0,
                        yOffset : 20
                    },
                    mobile : {
                        position : tawkposition,
                        xOffset : 0,
                        yOffset : 0
                    },
                    bubble : {
                        rotate : '0deg',
                         xOffset : -20,
                         yOffset : 0
                    }
                }
            };
        </script>
    </body>
</html>
