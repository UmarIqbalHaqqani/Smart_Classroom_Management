<section class="pb-themesection griddable {{ getClasses() }}" data-grid-name="{{ $grid }}" data-cols={!!
    json_encode($columns) !!}>
    <div {!! getContainerStyles() !!}>
        <div class="row pb-tooltip">
            @foreach ($columns as $item)
            <div class="{{$item}} droppable nested-sortable">
                <div class="pb-addsection pb-addsection-wrap removeable">
                    <div class="pb-addsection-info">
                        <svg class="pb-svg-border">
                            <rect width="100%" height="100%"></rect>
                        </svg>
                        <a href="javascript:;" class="iconPlus">
                            <i class="icon-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            @component('pagebuilder::components.grid-actions',['class'=>'','template_id'=>null,'id'=>'','droppable'=>false]) @endcomponent
        </div>
    </div>
</section>