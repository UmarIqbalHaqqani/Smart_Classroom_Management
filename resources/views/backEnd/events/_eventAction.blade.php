<x-drop-down>
    @if(userPermission('event-edit'))
        <a class="dropdown-item" href="{{route('event-edit',$event->id)}}">@lang('common.edit')</a>
    @endif
    @if(userPermission('delete-event-view') )
        <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="{{ __('communicate.delete_event') }}" href="{{route('delete-event-view',$event->id)}}">@lang('common.delete')</a>
    @endif
    @if($event->uplad_image_file != "")
        <a class="dropdown-item" href="{{url($event->uplad_image_file)}}" download>
            @lang('communicate.download') 
            <span class="pl ti-download"></span>
        </a>
    @endif
</x-drop-down>