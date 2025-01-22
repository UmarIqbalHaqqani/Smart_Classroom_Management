@php
    $total_fees = 0;
    $total_due = 0;
    $total_paid = 0;
    $total_disc = 0;
    $balance_fees = 0;
@endphp
<x-table>
    <div class="table-responsive">
        <table id="" class="table school-table-style-parent-fees" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="nowrap">@lang('fees.installment') </th>
                    <th class="nowrap">@lang('fees.amount') ({{ @generalSetting()->currency_symbol }})</th>
                    <th class="nowrap">@lang('common.status')</th>
                    <th class="nowrap">@lang('fees.due_date') </th>
                    <th class="nowrap">@lang('fees.payment_ID')</th>
                    <th class="nowrap">@lang('fees.mode')</th>
                    <th class="nowrap">@lang('fees.payment_date')</th>
                    <th class="nowrap">@lang('fees.discount') ({{ @generalSetting()->currency_symbol }})</th>
                    <th class="nowrap">@lang('fees.paid') ({{ @generalSetting()->currency_symbol }})</th>
                    <th class="nowrap">@lang('fees.balance')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->directFeesInstallments as $key => $feesInstallment)
                    @php
                        $total_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount);
                        $total_paid += $feesInstallment->paid_amount;
                        $total_disc += $feesInstallment->discount_amount;
                        $balance_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - $feesInstallment->paid_amount;
                    @endphp
                    <tr>
                        <td>{{ @$feesInstallment->installment->title }}</td>
                        <td>
                            @if ($feesInstallment->discount_amount > 0)
                                <del> {{ $feesInstallment->amount }} </del>
                                {{ $feesInstallment->amount - $feesInstallment->discount_amount }}
                            @else
                                {{ $feesInstallment->amount }}
                            @endif
                        </td>
                        <td>
                            <button
                                class="primary-btn small {{ fees_payment_status($feesInstallment->amount, $feesInstallment->discount_amount, $feesInstallment->paid_amount, $feesInstallment->active_status)[1] }} text-white border-0">{{ fees_payment_status($feesInstallment->amount, $feesInstallment->discount_amount, $feesInstallment->paid_amount, $feesInstallment->active_status)[0] }}
                            </button>
                        </td>
                        <td>{{ @dateConvert($feesInstallment->due_date) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> {{ $feesInstallment->discount_amount }}</td>
                        <td>
                            {{ $feesInstallment->paid_amount }}
                        </td>
                        <td>
                            {{ discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - $feesInstallment->paid_amount }}
                        </td>
                    </tr>
                    @php $this_installment = discount_fees($feesInstallment->amount, $feesInstallment->discount_amount); @endphp
                    @foreach ($feesInstallment->payments as $payment)
                        @php $this_installment = $this_installment - $payment->paid_amount; @endphp
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><img src="{{ asset('public/backEnd/img/table-arrow.png') }}"></td>
                            <td>
                                @if ($payment->active_status == 1)
                                    <a href="#" data-toggle="tooltip" data-placement="right"
                                        title="{{ 'Collected By: ' . @$payment->user->full_name }}">
                                        {{ @smFeesInvoice($payment->invoice_no) }}
                                    </a>
                                @endif
                            </td>
                            <td>{{ $payment->payment_mode }}</td>
                            <td>{{ @dateConvert($payment->payment_date) }}</td>
                            <td>{{ $payment->discount_amount }}</td>
                            <td>{{ $payment->paid_amount }}</td>
                            <td>{{ $this_installment }} </td>
                        </tr>
                    @endforeach
                @endforeach
            <tfoot>
                <tr>
                    <th>@lang('fees.grand_total') ({{ @$currency }})</th>
                    <th>{{ currency_format($total_fees) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ currency_format($total_disc) }}</th>
                    <th>{{ currency_format($total_paid) }} </th>
                    <th>{{ $total_fees - $total_paid }}</th>
                    {{-- <th></th> --}}
                </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
</x-table>
