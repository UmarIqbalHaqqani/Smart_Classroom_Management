<tbody>
    @if ($frontAcademicCalendars->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
        <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                href="{{ URL::to('/front-academic-calendar') }}">@lang('edulia.frontend_academic_calendar')</a></p>
    @else
        @foreach ($frontAcademicCalendars as $key => $data)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $data->title }}</td>
                <td>{{ date('d/m/Y', strtotime($data->publish_date)) }}</td>
                @if ($data->calendar_file)
                    <td class="pdf_download_option">
                        <a href="{{ asset($data->calendar_file) }}">
                            <i class="fas fa-download"></i> @lang('edulia.download')
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
</tbody>
