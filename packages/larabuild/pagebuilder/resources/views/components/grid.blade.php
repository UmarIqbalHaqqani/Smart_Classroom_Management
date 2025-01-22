@php
    if(!empty($template_id)){
        $componentSettings = getComponentSettings($template_id);
    }
@endphp
 

<div class="pb-addsection {{ $class ?? '' }} pb-addsection-wrap {{ $droppable ? 'removeable':'sectionable pb-tooltip' }}"
    id="{{ $id }}" data-section="{{ $template_id }}">
    {{ $slot }}
   
    @if (!empty($componentSettings))
    
        <div class="component-placeholder">
            <a href="javascript:void(0)" class="pb-elementcontent">
                {!! $componentSettings['icon'] !!}
                <span>{{ $componentSettings['name'] }}</span>
            </a>
        </div>
    @endif
    @component('pagebuilder::components.component-actions') @endcomponent
</div>