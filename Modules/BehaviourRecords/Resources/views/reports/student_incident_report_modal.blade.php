<div class="container-fluid">
    <div class="col-lg-12 mb-20">
        <div class="table-responsive">
        <table class="table" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th width="8%">@lang('behaviourRecords.title')
                    </th>
                    <th width="7%">@lang('behaviourRecords.point')
                    </th>
                    <th width="15%">@lang('behaviourRecords.session')
                    </th>
                    <th width="10%">@lang('behaviourRecords.date')
                    </th>
                    <th width="45%">@lang('behaviourRecords.description')
                    </th>
                    <th width="15%">@lang('behaviourRecords.assigned_by')
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all_incident as $incident)
                    <tr>
                        <td>{{ $incident->incident->title }}
                        </td>
                        <td>{{ $incident->incident->point }}
                        </td>
                        <td>{{ $incident->academicYear->year }} {{ $incident->academicYear->title }}
                        </td>
                        <td>{{ dateconvert($incident->incident->created_at) }}
                        </td>
                        <td>{{ $incident->incident->description }}
                        </td>
                        <td>{{ $incident->user->full_name }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
