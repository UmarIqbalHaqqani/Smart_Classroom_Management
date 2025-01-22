<x-drop-down>
    <a class="dropdown-item" href="{{route('alumni.view-transcript', [$row->id])}}" target="_blank">
        @lang('student.view_transcript')
    </a>
    @if (moduleStatusCheck('Alumni'))
        <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('alumni::al.add_alumni_information')" href="{{route('add-alumni-detail',[$row->id])}}" data-modal-size="modal-lg" title="@lang('alumni::al.add_alumni_information')">@lang('alumni::al.add_alumni_information')</a>
    @endif
    @if (!moduleStatusCheck('University'))
        <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('student.revert_as_student')" href="{{route('alumni.edit-revert-as-student',[$row->id])}}" data-modal-size="modal-lg" title="@lang('student.revert_as_student')" >@lang('student.revert_as_student')</a>
    @endif
</x-drop-down>