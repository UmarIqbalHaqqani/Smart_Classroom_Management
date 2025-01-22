@extends('pagebuilder::layouts.app')

@section('builder-content')
<div class="pb-bodywrapper">
    <div class="pb-preloader-outer">
        <img src="{{ asset(generalSetting()->preloader_image)}}" alt="#">
    </div>
    

    @component('pagebuilder::components.sidebar',
    ['componentTabs' => $componentTabs, 'components'=>$components,'page'=>$page])
    @endcomponent

    <main class="pb-main larabuild-grid1">
        <iframe id="pagebuilder-iframe" allowfullscreen="1" src="{{ URL('pages/'.$page->id.'/iframe') }}"
            frameborder="0"></iframe>
    </main>
</div>
@endsection


@section('builder-templates')

@if ($components)
@foreach ($components as $item)
@component('pagebuilder::components.grid',
['class'=>'d-none sectionable',
'template_id'=>$item['settings']['id'],'id'=>'template_'.$item['settings']['id'],'droppable'=>false])

<div class="pb-section-content section-data-{{ $item['settings']['id'] }} w-100">
    {!! $item['template'] !!}
</div>
@endcomponent
@endforeach
@endif

@endsection


@push('builder-js')
<script>
    // variables
    window.plusTemplate = `
        <div class="pb-addsection pb-addsection-wrap removeable">
            <div class="pb-addsection-info">
                <svg class="pb-svg-border">
                    <rect width="100%" height="100%"></rect>
                </svg>
                <a href="javascript:;" class="iconPlus">
                    <i class="icon-plus"></i>
                </a>
            </div>
        </div>`;
    var pageId      = '{{$page->id}}';
    window.gridTemplates = {!!json_encode($grid_templates)!!};
    window.sectionData = {!! json_encode($page->settings['section_data']??[]) !!};
    window.unsavedChanges = false;
    let pb_prefix = "{{config('pagebuilder.url_prefix')}}";
    if( pb_prefix != '' ){
        pb_prefix = pb_prefix+'/';
    }
    let ob_prefix = "{{config('optionbuilder.url_prefix')}}";
    if( ob_prefix != '' ){
        ob_prefix = ob_prefix+'/';
    }

    window.addEventListener("DOMContentLoaded", function(){
        $("#pagebuilder-iframe").on('load',function() {
            initBuilderJs();
        });
        $('.pb-elementcontent-wrapper').each(function(index, item) {
            new Sortable(item, {
                group: {
                    name: 'shared',
                    pull: 'clone',
                    put: false
                },

                sort: false,
                // animation: 0,
                // // fallbackOnBody: true,
                // revertClone: false,
                // chosenClass: "pb-choosen",
                setData: function (dataTransfer, dragEl) {
                    // Create the clone (with content)
                    console.log('test start');
                    console.log(dataTransfer);
                    dragGhost = dragEl.cloneNode(true);
                    // Stylize it
                    dragGhost.classList.add('pb-ghost');
                    // Place it into the DOM tree
                    document.body.appendChild(dragGhost);
                    // Set the new stylized "drag image" of the dragged element
                    dataTransfer.setDragImage(dragGhost, 0, 0);
                },
                onEnd: function(evt) {
                    dragGhost.parentNode.removeChild(dragGhost);
                    $('#pagebuilder-iframe').contents().find('.pb-addsection-info').removeClass('pb-shortcode-drop');
                    $('#pagebuilder-iframe').contents().find('.removeable').show(); 
                },
            });
        });
    });

    function extractJs(section){
        $(document).find('script').each(function(){
            if($(this).attr("section")==section){
                var text = $(this).text();
                eval(text);
            }
        });
    }

    
    function initBuilderJs() {
        let grids = document.getElementById('pagebuilder-iframe').contentWindow.document.getElementById('grids')
        new Sortable.create(grids, {
            sort: true,
            scroll: true,
            scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
            scrollSpeed: 10, // px, speed of the scrolling
            bubbleScroll: true, // apply autoscroll to all parent elements, allowing for easier movement
            // revertOnSpill: true,
            animation: 150,
            handle: '.grid-handle',
            ghostClass: "pb-placeholder",
            draggable: ".griddable",
        });

        let nestedSortables = document.getElementById('pagebuilder-iframe').contentWindow.document.getElementsByClassName('nested-sortable');
        // Loop through each nested sortable element
        for (var i = 0; i < nestedSortables.length; i++) {
            new Sortable(nestedSortables[i], {
                group: {
                    name: 'shared',
                    put: ["shared"],
                },
                scroll: true,
                scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
                scrollSpeed: 10, // px, speed of the scrolling
                bubbleScroll: true, // apply autoscroll to all parent elements, allowing for easier movement
                // revertOnSpill: true,
                sort: true, // sorting inside list
                ghostClass: "pb-placeholder", // Class name for the drop placeholder
                // draggable: ".droppable",
                animation: 150,
                // filter:'.griddable',
                // fallbackOnBody: false,
                easing: "cubic-bezier(0.83, 0, 0.17, 1)",
                handle: '.component-handle',
                // dragoverBubble: false,
                // revertClone: true,
                onChange: function(evt) {
                    $('#pagebuilder-iframe').contents().find('.removeable').show();
                    $('#pagebuilder-iframe').contents().find('.pb-addsection-info').removeClass('pb-shortcode-drop');
                    if ($(evt.to).find('.pb-addsection-info').length > 0) {
                        $(evt.to).find('.removeable').hide();
                        $(evt.to).find('.pb-addsection-info').addClass('pb-shortcode-drop');
                    }
                },
                onStart:function(evt){
                    $(evt.item).find('.pb-section-content').hide();
                },
                setData: function (dataTransfer, dragEl) {
                    // Create the clone (with content)
                    dragGhost = dragEl.cloneNode(true).querySelector('.component-placeholder');
                    // Stylize it
                    console.log('test start');
                    console.log(dragGhost);

                    dragGhost.classList.add('pb-ghost');
                    // Place it into the DOM tree
                    document.body.appendChild(dragGhost);
                    // Set the new stylized "drag image" of the dragged element
                    dataTransfer.setDragImage(dragGhost, 0, 0);
                },
                // Don't forget to remove the ghost DOM object when done dragging
                onEnd: function (evt) {
                    dragGhost.parentNode.removeChild(dragGhost);
                    $(evt.item).find('.pb-section-content').show(); 
                },
                onAdd: function(evt) {
                    if ($(evt.item).hasClass('draggable')) {
                        let id = $(evt.clone).data('section');
                        let uniqueId = window.getUniqueId();
                        let cloneHtml = $('#template_' + id).clone(true).detach().removeClass('d-none').removeAttr('id').attr('id', uniqueId);

                        $(evt.item).replaceWith(cloneHtml);
                        getSectionSettings(uniqueId);
                        initBuilderJs();
                        window.unsavedChanges = true;
                        $(evt.to).find('.removeable').remove();
                    }
                    if ($(evt.from).find('.sectionable').length < 1  && $(evt.from).hasClass('droppable')) {
                        $(evt.from).append(window.plusTemplate);
                    }
                    if ($(evt.to).find('.removeable').length == 1) {
                        $(evt.to).find('.removeable').remove();
                    }
                    $('#pagebuilder-iframe').contents().find('.pb-addsection-info').removeClass('pb-shortcode-drop');
                }
            });
        
        }
    }

    window.getUniqueId =function(){
        return Date.now().toString(20);
    }

    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    function manageContentWidth(content_width, width=1320){
        let gridId = $('#current-grid-id').val();
        if(content_width == 'full_width'){
            $('#pagebuilder-iframe').contents().find('#'+gridId+' div[class^="container"]:first').removeClass('container').addClass('container-fluid').removeAttr('style');
        }
        else if(content_width == 'boxed'){
            $('#pagebuilder-iframe').contents().find('#'+gridId+' div[class^="container"]:first').removeClass('container-fluid').addClass('container').attr('style', 'max-width:'+width+'px');
        }
        else{
            $('#pagebuilder-iframe').contents().find('#'+gridId+' div[class^="container"]:first').removeClass('container-fluid').addClass('container').removeAttr('style');
        }
    }

    function setPageSettings(){
    var mainArray = {
        'grids': [],
        'section_data': [],
    };

    let iframe = $('#pagebuilder-iframe').contents();

    iframe.find('.griddable').each(function(index, item) {
        let _this = $(this); 
        let cols = _this.data('cols');

        let data = [];
        _this.find('.droppable').each(function(colIndex, colItem) {
            let _col = $(this);
            let sections = [];
            _col.find('.sectionable').each(function(sectionIndex, section){
                sections.push({
                    'id': section.id,
                    'section_id': section.getAttribute('data-section'),
                    'position': sectionIndex
                });
            });
            data.push(sections);
        });

        mainArray.grids.push({
            'grid': _this.data('grid-name'),
            'position': index,
            'grid_id': _this.attr('id'),
            'data': data
        });
    });

    mainArray.section_data = window.sectionData;

    let section_id = $('#current-section-id').val();
    let grid_id = $('#current-grid-id').val();
    let form_id = 'current-section-form';

    let currentSectionData = '';
    let currentSectionStyles = '';
    let directory = '';

    if(section_id !== ''){
        currentSectionData = $('#current-section-form').serialize();
        currentSectionStyles = $('#current-advanced-settings-form').serialize();
        directory = $('#pagebuilder-iframe').contents().find('#' + section_id).data("section");
    }
    
    let ajaxUrl = `{{ url('${pb_prefix}set-page-settings') }}`;

    if(pageId && ajaxUrl){
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                'page_id': pageId,
                'settings': mainArray,
                'current_section_data': currentSectionData,
                'current_advanced_settings': currentSectionStyles,
                'directory': directory
            },
            dataType: 'json',
            success: function(data){
                $('.savePageData').removeClass('pb-btn-actionload');
                window.unsavedChanges = false;
                if(data.success){
                    if(grid_id){
                        let content_width = $('#content_width').select2("val"); 
                        if(content_width){
                            manageContentWidth(content_width, $('#boxed_slider_input').val());
                        }
                        $('#pagebuilder-iframe').contents().find('#'+grid_id).removeAttr('style');
                        $('#pagebuilder-iframe').contents().find('#'+grid_id + ' .pb-bg-overlay').remove();

                        if(data.bgOverlay){
                            data.css += 'position:relative';
                            $('#pagebuilder-iframe').contents().find('#'+grid_id).prepend(data.bgOverlay); 
                        }

                        if(data.css){
                            $('#pagebuilder-iframe').contents().find('#'+grid_id).attr('style',data.css);
                        }
                        $('#pagebuilder-iframe').contents().find('#'+grid_id).removeAttr('class');
                        $('#pagebuilder-iframe').contents().find('#'+grid_id).attr('class', 'pb-themesection griddable '+data.classes);
                    }

                    if(section_id){
                        if(data.html){
                            $('#pagebuilder-iframe').contents().find('#' + section_id + ' .section-data-' + directory).html(data.html);
                        }

                        if(directory){
                            extractJs(directory);
                        }  
                    }
                    window.unsavedChanges = false;
                    window.sectionData = data.sectionData;
                }
            },
            error: function(error){
                $('.savePageData').removeClass('pb-btn-actionload');
                window.unsavedChanges = false;
            }
        });
    }

    // document.getElementById("pagebuilder-iframe").contentDocument.location.reload(true);
}


    function getSectionSettings(sectionId) {
        let section = $('#pagebuilder-iframe').contents().find('#'+sectionId);
        let iframe = window.frames['pagebuilder-iframe'].contentDocument.getElementById(sectionId);
        
        let directory = section.data('section');
        let pageId = '{{ $page->id }}';
        let gridId = section.closest('.griddable').attr('id');

        if (directory) {

            $.ajax({
                type: 'POST',
                url: `{{ url('${pb_prefix}get-section-settings') }}`,
                data: {
                    'directory': directory,
                    'page_id': pageId,
                    'id': sectionId,
                    'grid_id': gridId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.type == 'success') {
                        $('#current-grid-id').val(gridId);
                        $('#current-section-id').val(sectionId);
                        if (data.settings) {
                            $('#section-settings-wrapper').html(data.settings);
                        }
                        else{
                            $('#section-settings-wrapper').html('<span class="at-empty-settings">{{ __("pagebuilder::pagebuilder.no_settings") }}</span>');
                        }
                        $('#settings-btn').tab('show');   
                        $('#advanced-settings-wrapper').html(data.styles);
                    }
                },
                complete:function(){
                    initializeScripts();
                    setPageSettings();
                },
                error:function(data){
                    showAlert({
                        message     : '{{ __("pagebuilder::pagebuilder.alert_error_message") }}',
                        type        : 'error',
                        title       : '{{ __("pagebuilder::pagebuilder.alert_error_title") }}',
                        autoclose   : 2000,
                    }); 
                }
            });
        }
    }

    window.disableAnchors = function(selector){
        selector.find('a').each(function(index,item){
            item.href = 'javascript:;';
        });
    }

    function resetWidthHeights(){
        $('input[name="width"]').val('');
        $('input[name="height"]').val('');
        $('input[name="min-width"]').val('');
        $('input[name="min-height"]').val('');
        $('input[name="max-width"]').val('');
        $('input[name="max-height"]').val('');
    }

    function resetMargins(){
        $('input[name="margin-top"]').val('');
        $('input[name="margin-right"]').val('');
        $('input[name="margin-bottom"]').val('');
        $('input[name="margin-left"]').val('');
    }

    function resetPaddings(){
        $('input[name="padding-top"]').val('');
        $('input[name="padding-right"]').val('');
        $('input[name="padding-bottom"]').val('');
        $('input[name="padding-left"]').val('');
    }

    window.onload = (event) => {
        jQuery(document).ready(function() {
            jQuery(".pb-preloader-outer").delay(200).fadeOut();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $(document).on('click', '.savePageData', function(e) {
                let _this = jQuery(this);
                _this.addClass('pb-btn-actionload');
                setTimeout(() => {
                    setPageSettings();
                }, 100);
            });

            $('#current-section-form,#current-advanced-settings-form').on('input keyup' , debounce(function(e){
                if ((e.which <= 65 &&  e.which != 32 && e.which != 8) || e.which > 90 || typeof e.which == 'undefined') 
                    return false;
                setPageSettings();
                },100)
            );

            // summernote.change
            $(document).on('summernote.keyup','.op-editor', debounce(function(we, contents, $editable) {
                we.target.innerHTML = contents;
                setPageSettings();
                },100)
            );

            $(document).on('click','.more-mul-rep,.more-single-rep',function(){
                setTimeout(() => {
                    setPageSettings();
                }, 100);
            });


            $('#current-section-form,#current-advanced-settings-form').change(function(){
                setPageSettings();
            });

            $(document).on('click', '.op-colorpicker .cp-buttons .btn',  function(){
                setPageSettings();
            });
            $(document).on('click', '.op-trash-mul-rep,.op-trash-single-rep',  function(){
                setTimeout(() => {
                    setPageSettings();
                }, 100);
            });

            $(document).on('click', '.op-remove-file .icon-trash-2', function(event){
                setTimeout(() => {
                    setPageSettings();
                }, 100);
            });

            $(document).on('change', 'input[name="width-height-type"]', function(event){
                event.preventDefault();
                resetWidthHeights();
                setPageSettings();
            });

            $(document).on('change', 'input[name="margin-type"]', function(event){
                event.preventDefault();
                resetMargins();
                setPageSettings();
            });

            $(document).on('change', 'input[name="padding-type"]', function(event){
                event.preventDefault();
                resetPaddings();
                setPageSettings();
            });

            $(document).on('change', '.op-uploads-img-data input[type=file]', function(event){
                
                let _this = $(this);
                let timestamp       = Date.now();
                let multi_items 	= _this.data('multi_items');
                let fieldId 	    = _this.data('id');
                let extensions      = _this.data('ext');
                let repeater_id 	= _this.data('repeater_id');
                let parent_rep 		= _this.data('parent_rep');
                let max_size        =  0;
                if(_this.data('max_size') !=''){
                    max_size = Number(_this.data('max_size')) * 1024;
                }
                let skeleton = `<li class="op-upload-img-info ob-file-skel">
                    <div class="op-uploads-img-data">
                        <label class="lb-spinner">
                            <div class="spinner-grow"></div>
                        </label>
                    </div>
                </li>`;
                let clonedItem 	    = '';
                const files         = event.target.files; 
                let formData        = new FormData;
                for(let i = 0; i < files.length; i++){
                    const fsize = Math.round((files[i].size/1024));
                    if( fsize > max_size ){
                        showAlert({
                                message     : '{{__("optionbuilder::option_builder.max_file_size")}}',
                                type        : 'error',
                                title       : '{{__("optionbuilder::option_builder.error_title")}}' ,
                                autoclose   :  1000,
                            });
                        return false;
                    }
                    formData.append(`files[${files[i].name}]`, files[i]);
                    _this.parents('.op-upload-img-info').after(skeleton);
                    // $(skeleton).insertAfter();
                }
                formData.append('extensions', extensions);
                event.target.value = '';
                $.ajax({
                    url: `{{ url('${ob_prefix}option-builder/upload-files') }}`,
                    method: 'post',
                    contentType: false,
                    processData: false,
                    data:  formData,
                    success: function(data){
                        _this.parents('.op-upload-img').find('.ob-file-skel').remove();
                        if( data.type == 'success'){
                            if( data.files ){
                                data.files.forEach(function( file, index ) {
                                    let _thumbnail = _this.parents('.op-upload-img-info').next('li.op-img-thumbnail');
                                    if(  multi_items === false ){
                                        _this.parents('.op-upload-img').find('.op-img-thumbnail').not(':first').remove();
                                        let item = _thumbnail.first();
                                        clonedItem 	= item;
                                    }else if( multi_items === true ){
                                        let item = _thumbnail.first();
                                        clonedItem 	= item.clone();
                                    }

                                    if( typeof repeater_id != 'undefined' && repeater_id != null  ){
                                        if( typeof parent_rep != 'undefined' && parent_rep != null  ){
                                            _this.parents('.op-upload-img').find('.op-img-thumbnail input[type=hidden]').each((index,i) => {
                                                if(i.value !=''){
                                                    $(i).attr('name',`${parent_rep}[${repeater_id}][${timestamp}][${fieldId}][]`)
                                                    .attr('value',i.value);
                                                }
                                            });
                                            clonedItem.find("input[type='hidden']").attr('name',`${parent_rep}[${repeater_id}][${timestamp}][${fieldId}][]`);
                                        }else{
                                            clonedItem.find("input[type='hidden']").attr('name',`${repeater_id}[${fieldId}][]`);
                                        }
                                    }else{
                                        clonedItem.find("input[type='hidden']").attr('name',`${fieldId}[]`);
                                    }
                                    clonedItem.find('img').attr('src', file.thumbnail);    
                                    clonedItem.find("input[type='hidden']").val(JSON.stringify(file));
                                    _this.parents('.op-upload-img-info').last('li .op-img-thumbnail').after(clonedItem);
                                    clonedItem.removeClass('d-none');
                                });
                            }
                            setPageSettings();
                        }else{
                        showAlert({
                                message     : data.message,
                                type        : 'error',
                                title       : data.title        ? data.title : '' ,
                                autoclose   : data.autoClose    ? data.autoClose : 1000,
                            }); 
                        }
                    }
                });
            });

            $(window).on('beforeunload', function () {
                if (window.unsavedChanges) {
                    return '{{ __("pagebuilder::pagebuilder.changes_not_saved") }}';
                }
            });
        });

        $(window).on('getSectionSettings', function(event, sectionId) {
            getSectionSettings(sectionId);
        });
        $(window).on('initBuilderJs', function(event) {
            initBuilderJs();
        });

    }

</script>
@endpush