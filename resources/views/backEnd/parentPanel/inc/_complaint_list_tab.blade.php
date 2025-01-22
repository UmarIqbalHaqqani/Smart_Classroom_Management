@if (isset($complaints))
    <div class="row mb-15">
        <div class="col-lg-4 no-gutters">
            <div class="main-title">
                <h3 class="mb-0">@lang('admin.complaint') @lang('admin.list')</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <x-table>
                <div class="table-responsive">
                    <table id="table_id" class="table data-table Crm_table_active3" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('common.sl')</th>
                                <th>@lang('admin.complaint_by')</th>
                                <th>@lang('admin.complaint_type')</th>
                                <th>@lang('admin.source')</th>
                                <th>@lang('admin.phone')</th>
                                <th>@lang('admin.date')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$complaints as $key => $complaint)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ @$complaint->complaint_by }}</td>
                                    <td>{{ isset($complaint->complaint_type) ? @$complaint->complaintType->name : '' }}
                                    </td>
                                    <td>{{ isset($complaint->complaint_source) ? @$complaint->complaintSource->name : '' }}
                                    </td>
    
                                    <td>{{ $complaint->phone }}</td>
                                    <td data-sort="{{ strtotime(@$complaint->date) }}">
                                        {{ !empty(@$complaint->date) ? dateConvert(@$complaint->date) : '' }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-table>
        </div>
    </div>
@endif
