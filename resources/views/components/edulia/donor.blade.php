@if ($donors->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/donor') }}">@lang('edulia.donor')</a></p>
@else
    @foreach ($donors as $key => $data)
        <tr>
            <td>
                {{ $key + 1 }}
            </td>
            <td><img src="{{ asset($data->photo) }}" class="user_img" alt=""></td>
            <td><a href="{{ route('frontend.donor-details', $data->id) }}"
                    class="link_to_details"><b>{{ $data->full_name }}</b></a></td>
            <td>{{ $data->profession }}</td>
            <td>{{ $data->email }}</td>
            <td>{{ $data->mobile }}</td>
            <td>{{ $data->religion->base_setup_name }}</td>
            <td>{{ $data->gender->base_setup_name }}</td>
            <td class="blood_group">{{ $data->bloodGroup->base_setup_name }}</td>
        </tr>
    @endforeach
@endif
