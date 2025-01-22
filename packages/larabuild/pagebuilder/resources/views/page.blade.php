@extends(config('pagebuilder.site_layout'),['page' => $page, 'edit' => false ])

@section(config('pagebuilder.site_section'))
{!! $headerMenu !!}
{!! $pageSections !!}
{!! $footerMenu !!}
@endsection

@if(moduleStatusCheck('WhatsappSupport'))
     @include('whatsappsupport::partials._popup')
@endif 
