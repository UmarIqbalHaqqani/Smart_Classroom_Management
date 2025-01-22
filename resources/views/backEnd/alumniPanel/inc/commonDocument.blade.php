<div class="white-box">
    <div class="row">
        <div class="col-lg-6 col-7">
            <div class="main-title">
                <h3 class="mb-15">@lang('alumni::al.documents')</h3>
            </div>
        </div>
        <div class="col-lg-12">
            <table class="school-table-style w-100">
                <thead>
                    <tr>
                        <th>@lang('dashboard.title')</th>
                        <th>@lang('common.date')</th>
                        <th class="d-flex justify-content-around">@lang('common.actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @php $role_id = Auth()->user()->role_id; @endphp
                    @if (isset($documents)) 
                        @foreach ($documents as $doc)
                            <tr>
                                <td>{{ @$doc->title }}</td>
                                <td> @if ($doc->created_at){{ dateConvert($doc->created_at) }} @endif </td>
                                <td class="d-flex justify-content-around">
                                    <a href="{{ route('alumni-view-document', $doc->id) }}" title="@lang('alumni::al.view_document')"
                                        class="primary-btn small tr-bg modalLink" data-modal-size="modal-lg">
                                        <span class="ti-eye"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>