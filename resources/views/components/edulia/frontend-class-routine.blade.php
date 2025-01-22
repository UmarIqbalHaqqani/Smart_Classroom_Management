@if ($frontClassRoutines->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/front-class-routine') }}">@lang('edulia.class_routine')</a></p>
@else
    @foreach ($frontClassRoutines as $key => $frontClassRoutine)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $frontClassRoutine->title }}</td>
            <td>{{ date('d/m/Y', strtotime($frontClassRoutine->publish_date)) }}</td>
            @if ($frontClassRoutine->result_file)
                <td class="pdf_download_option">
                    <a href="{{ asset($frontClassRoutine->result_file) }}">
                        <i class="fas fa-file"></i> @lang('edulia.download')
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
@endif
