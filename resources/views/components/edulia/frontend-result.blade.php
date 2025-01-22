@if ($frontResults->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/front-result') }}">@lang('edulia.result')</a></p>
@else
    @foreach ($frontResults as $key => $frontResult)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $frontResult->title }}</td>
            <td>{{ date('d/m/Y', strtotime($frontResult->publish_date)) }}</td>
            @if ($frontResult->result_file)
                <td class="pdf_download_option">
                    <a href="{{ asset($frontResult->result_file) }}">
                        <i class="fas fa-file"></i> @lang('edulia.download')
                    </a>
                </td>
            @endif
            @if ($frontResult->link)
                <td class="pdf_download_option">
                    <a href="{{ $frontResult->link }}"
                        target="_blank">
                        <i class="fas fa-link"></i> @lang('edulia.link')
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
@endif
