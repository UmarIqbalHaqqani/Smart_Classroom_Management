@if ($frontExamRoutines->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/front-exam-routine') }}">@lang('edulia.exam_routine')</a></p>
@else
    @foreach ($frontExamRoutines as $key => $frontExamRoutine)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $frontExamRoutine->title }}</td>
            <td>{{ date('d/m/Y', strtotime($frontExamRoutine->publish_date)) }}</td>
            @if ($frontExamRoutine->result_file)
                <td class="pdf_download_option">
                    <a href="{{ asset($frontExamRoutine->result_file) }}">
                        <i class="fas fa-file"></i> @lang('edulia.download')
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
@endif
