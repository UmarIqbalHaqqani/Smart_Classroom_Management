<div class="col-lg-12 no-gutters d-flex align-items-center justify-content-between">
    <div class="main-title">
        <h3 class="mb-15">@lang('fees.fees')
        </h3>
    </div>
</div>
<div class="col-lg-12 student-details up_admin_visitor">
    <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">
        @foreach ($children->studentRecords as $key => $record)
            <li class="nav-item mb-0">
                <a class="nav-link mb-0 @if ($key == 0) active @endif "
                    href="#feesTab{{ $record->id }}" role="tab"
                    data-toggle="tab">{{ $record->class->class_name }}
                    ({{ $record->section->section_name }})
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ($children->studentRecords as $key => $record)
            <div role="tabpanel"
                class="tab-pane fade  @if ($key == 0) active show @endif"
                id="feesTab{{ $record->id }}">
                @if (generalSetting()->fees_status == 0)
                    @includeIf('backEnd.parentPanel.inc._student_direct_fees')
                @else
                    <x-table>
                        <div class="table-responsive">
                            <table id="default_table" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('common.student')</th>
                                        <th>@lang('student.class_section')</th>
                                        <th>@lang('accounts.amount')</th>
                                        <th>@lang('fees::feesModule.waiver')</th>
                                        <th>@lang('fees.fine')</th>
                                        <th>@lang('fees.paid')</th>
                                        <th>@lang('accounts.balance')</th>
                                        <th>@lang('common.status')</th>
                                        <th>@lang('common.date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($record->feesInvoice as $key => $studentInvoice)
                                        @php
                                            $amount = $studentInvoice->Tamount;
                                            $weaver = $studentInvoice->Tweaver;
                                            $fine = $studentInvoice->Tfine;
                                            $paid_amount = $studentInvoice->Tpaidamount;
                                            $sub_total = $studentInvoice->Tsubtotal;
                                            $balance = $amount + $fine - ($paid_amount + $weaver);
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('fees.fees-invoice-view', ['id' => $studentInvoice->id, 'state' => 'view']) }}"
                                                    target="_blank">
                                                    {{ @$children->full_name }}
                                                </a>
                                            </td>
                                            <td>{{ @$record->class->class_name }}
                                                ({{ @$record->section->section_name }})
                                            </td>
                                            <td>{{ $currency->currency_symbol }}{{ $amount }}</td>
                                            <td>{{ $currency->currency_symbol }}{{ $weaver }}</td>
                                            <td>{{ $currency->currency_symbol }}{{ $fine }}</td>
                                            <td>{{ $currency->currency_symbol }}{{ $paid_amount }}</td>
                                            <td>{{ $currency->currency_symbol }}{{ $balance }}</td>
                                            <td>
                                                @if ($balance == 0)
                                                    <button
                                                        class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                                                @else
                                                    @if ($paid_amount > 0)
                                                        <button
                                                            class="primary-btn small bg-warning text-white border-0">@lang('fees.partial')</button>
                                                    @else
                                                        <button
                                                            class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ dateConvert($studentInvoice->create_date) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-table>
                @endif
            </div>
        @endforeach
    </div>
</div>
