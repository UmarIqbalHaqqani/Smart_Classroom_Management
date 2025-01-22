<section class="sms-breadcrumb mb-20 up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>{{ isset($h1) ? $h1 : ''}}</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                @isset($bgPages)
                    @foreach ($bgPages as $page)
                        {!! $page !!}
                    @endforeach
                @endisset
                <a href="#">{{ isset($h1) ? $h1 : ''}}</a>
            </div>
        </div>
    </div>
</section>