<div class="table-responsive">
<table class="table" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="10%">@lang('behaviourRecords.title')</th>
            <th width="8%">@lang('behaviourRecords.point')</th>
            <th width="12%">@lang('behaviourRecords.date')</th>
            <th width="45%">@lang('behaviourRecords.description')</th>
            <th width="15%">@lang('behaviourRecords.assigned_by')</th>
            <th width="10%">@lang('behaviourRecords.actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($student->incidents as $data)
            <tr>
                <input type="hidden" name="incident_id" id="incident_id" value="{{ $data->id }}">
                <td>{{ $data->incident->title }}</td>
                <td>{{ $data->incident->point }}</td>
                <td>{{ dateconvert($data->incident->created_at) }}</td>
                <td>{{ $data->incident->description }}</td>
                <td>{{ $data->user->full_name }}</td>
                <td>
                    <a class="primary-btn small fix-gr-bg" onclick="assignViewDelete({{ $data->id }})"
                        href="#">@lang('behaviourRecords.delete')</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</div>