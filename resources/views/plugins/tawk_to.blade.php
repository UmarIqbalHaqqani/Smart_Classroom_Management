{{-- @if ($tawk_setting->showing_page == 'all')
     @if ( ($tawk_setting->availability == 'mobile' && $agent->isMobile()) ||
         ($tawk_setting->availability == 'desktop' && $agent->isDesktop()) || $tawk_setting->availability == 'both')

          @if ($tawk_setting->show_admin_panel == 1)
               @push('script')
                    {{!! $tawk_setting->short_code!!}}
               @endpush
          @endif 

     @endif
@endif --}} 
@php
    $isApplicable = false;

    if (Auth::check()) {
        $user = Auth::user();

        if ($user) {
            $userRole = $user->role_id;
            $applicableRoles = $tawk_setting->applicable_for;
            $isApplicable = is_array($applicableRoles) && in_array($userRole, $applicableRoles);
        }
    }
@endphp

 @if($tawk_setting->is_enable == 1)
    @if ($tawk_setting->showing_page == 'all')
        @if ( ($tawk_setting->availability == 'mobile' && $agent->isMobile()) ||
        ($tawk_setting->availability == 'desktop' && $agent->isDesktop()) ||      
            $tawk_setting->availability == 'both')

            @if($isApplicable)
                @if ($tawk_setting->show_admin_panel == 1 || $tawk_setting->show_website == 1)
                    {{ $tawk_setting->short_code }}
                @endif
            @endif
        @endif
    @elseif($tawk_setting->showing_page == 'homepage' && Str::startsWith(Route::currentRouteName(), 'frontEnd.home'))
        @if (($tawk_setting->availability == 'mobile' && $agent->isMobile()) ||
            ($tawk_setting->availability == 'desktop' && $agent->isDesktop()) ||      
                $tawk_setting->availability == 'both')

            @if($isApplicable)
                @if ($tawk_setting->show_admin_panel == 1 || $tawk_setting->show_website == 1)
                        {{ $tawk_setting->short_code }}
                @endif
            @endif
        @endif
    @endif
@endif 