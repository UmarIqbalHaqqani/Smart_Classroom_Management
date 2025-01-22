@push('css')
<style>
    .table-responsive .table-alignment tr th, .table-responsive .table-alignment tr td{
        min-width: 150px;
    }
</style>
@endpush

<div role="tabpanel" class="tab-pane fade" id="studentBehaviourRecord">
    <div>
        <div class="table-responsive">
            <table class="table table-alignment" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th width="15%">@lang('behaviourRecords.title')</th>
                    <th width="10%">@lang('behaviourRecords.point')</th>
                    <th width="10%">@lang('behaviourRecords.date')</th>
                    <th width="45%">@lang('behaviourRecords.description')</th>
                    <th width="10%">@lang('behaviourRecords.assigned_by')</th>
                    <th width="10%">@lang('behaviourRecords.actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentBehaviourRecords as $data)
                    <tr>
                        <td width="15%">{{ $data->incident->title }}</td>
                        <td width="10%">{{ $data->incident->point }}</td>
                        <td width="10%">{{ dateconvert($data->incident->created_at) }}</td>
                        <td width="45%">{{ $data->incident->description }}</td>
                        <td width="10%">{{ $data->user->full_name }}</td>
                        <td width="10%">
                            <x-drop-down>
                                @if (auth()->user()->role_id == 1)
                                    <a class="dropdown-item"
                                        href="{{ route('behaviour_records.incident_comment', [$data->id]) }}">@lang('behaviourRecords.comment')</a>
                                @elseif (auth()->user()->role_id == 2)
                                    @if ($behaviourRecordSetting->student_comment == 1)
                                        <a class="dropdown-item"
                                            href="{{ route('behaviour_records.incident_comment', [$data->id]) }}">@lang('behaviourRecords.comment')</a>
                                    @endif
                                @else
                                    @if ($behaviourRecordSetting->parent_comment == 1)
                                        <a class="dropdown-item"
                                            href="{{ route('behaviour_records.incident_comment', [$data->id]) }}">@lang('behaviourRecords.comment')</a>
                                    @endif
                                @endif
                            </x-drop-down>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
