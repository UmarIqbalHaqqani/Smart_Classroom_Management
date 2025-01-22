@php
    $isApplicable = false;

    if (Auth::check()) {
        $user = Auth::user();

        if ($user) {
            $userRole = $user->role_id;
            $applicableRoles = $messenger_setting->applicable_for;
            $isApplicable = is_array($applicableRoles) && in_array($userRole, $applicableRoles);
        }
    }
@endphp
@if($messenger_setting->is_enable == 1)

    @if ($messenger_setting->showing_page == 'all')

        @if ( ($messenger_setting->availability == 'mobile' && $agent->isMobile()) ||
        ($messenger_setting->availability == 'desktop' && $agent->isDesktop()) ||      
            $messenger_setting->availability == 'both')

            @if($isApplicable)
                @if ($messenger_setting->show_admin_panel == 1 || $tawk_settin->show_website == 1)
                    {!! $messenger_setting->short_code!!}
                @endif
            @endif
        @endif
    @elseif($messenger_setting->showing_page == 'homepage' && Str::startsWith(Route::currentRouteName(), 'frontEnd.home'))
        @if (($messenger_setting->availability == 'mobile' && $agent->isMobile()) ||
            ($messenger_setting->availability == 'desktop' && $agent->isDesktop()) ||      
                $messenger_setting->availability == 'both')

            @if($isApplicable)
                @if ($messenger_setting->show_admin_panel == 1 || $tawk_settin->show_website == 1)
                  
                        {!! $messenger_setting->short_code!!}
                   
                @endif
            @endif
        @endif
    @endif
@endif
