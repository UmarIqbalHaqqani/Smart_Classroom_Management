<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFeesInvoiceChieldViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $subTotal = $this->sum('sub_total');
        $paidAmount = $this->sum('paid_amount');
        /* $due = $subTotal - $paidAmount;
        if ($due == 0) {
            $paymentStatus = 'Paid';
        } else {
            if ($paidAmount > 0) {
                $paymentStatus = 'Partial';
            } else {
                $paymentStatus = 'Unpaid';
            }
        } */

        $amount = 0;
        $weaver = 0;
        $paid_amount = 0;
        $fine = 0;
        $service_charge = 0;
        $grand_total = 0;
        $balance = 0;

        $amount += $this->amount;
        $weaver += $this->weaver;
        $fine += $this->fine;
        $service_charge += $this->service_charge;
        $paid_amount += $this->paid_amount;

        $totalAmount = ($this->amount + $this->fine) - $this->weaver;
        $grand_total += $totalAmount;

        $total = ($this->amount + $this->fine) - ($this->paid_amount + $this->weaver);
        $balance += $total;


        return [
            'sub_total' => currency_format($subTotal) ? currency_format($subTotal) : '0',
            'paid_amount' => currency_format($paidAmount) ? currency_format($paidAmount) : '0',
            /* 'payment_status' => $paymentStatus,
            'fees_type' => $this->feesType->name,
            'note' => $this->note, */
            'amount' => currency_format($this->amount) ? currency_format($this->amount) : '0',
            'weaver' => currency_format($this->weaver) ? currency_format($this->weaver) : '0',
            'fine' => currency_format($this->fine) ? currency_format($this->fine) : '0',
            // 'total' => currency_format($total)?currency_format($total):'0',
            'total_amount' => currency_format($amount) ? currency_format($amount) : '0',
            'total_waiver' => currency_format($weaver) ? currency_format($weaver) : '0',
            'total_fine' => currency_format($fine) ? '$' . currency_format($fine) : '0',
            // 'total_service_charge' => currency_format($service_charge)?currency_format($service_charge):'0',
            'total_paid' => currency_format($paid_amount) ? currency_format($paid_amount) : '0',
            'grand_total' => currency_format($grand_total + $service_charge) ? currency_format($grand_total + $service_charge) : '0',
            'due_balance' => currency_format($balance) ? currency_format($balance) : '0',
        ];
    }
}
