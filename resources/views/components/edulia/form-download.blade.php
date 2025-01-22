@if ($formDownloads->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/form-download') }}">@lang('edulia.form_download')</a></p>
@else
    @foreach ($formDownloads as $key => $data)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $data->title }}</td>
            <td>{{ $data->short_description }}</td>
            <td>{{ date('d/m/Y', strtotime($data->publish_date)) }}</td>
            @if ($data->file)
                <td class="pdf_download_option">
                    <a href="{{ asset($data->file) }}">
                        <i class="fas fa-file"></i> @lang('edulia.download')
                    </a>
                </td>
            @endif
            @if ($data->link)
                <td class="pdf_download_option">
                    <a href="{{ $data->link }}"
                        target="_blank">
                        <i class="fas fa-link"></i> @lang('edulia.link')
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
@endif
