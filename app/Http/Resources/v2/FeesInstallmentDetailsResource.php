<?php

namespace App\Http\Resources\v2;

use App\Models\FeesInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeesInstallmentDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $invoice_settings = FeesInvoice::where('school_id', auth()->user()->school_id)->first();
        $this_installment = discount_fees($this->amount, $this->discount_amount);

        return [
            'id' => (int)$this->id,
            'collected_by' => (string)@$this->user->full_name,
            'payment_id' => (string)@sm_fees_invoice($this->invoice_no, $invoice_settings),
            'mode' => (string)$this->payment_mode,
            'payment_date' => (string)dateConvert($this->payment_date),
            'discount_amount' => (float)$this->discount_amount,
            'paid' => (float)$this->paid_amount,
            'balance' => (float)$this_installment = $this_installment - $this->paid_amount,
            'payment_status' => (string)__('fees.paid'),
        ];
    }
}
