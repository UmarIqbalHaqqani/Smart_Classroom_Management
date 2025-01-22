<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeesInstallmentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if(@$this->discount_amount > 0)
            $amount = @$this->amount - @$this->discount_amount;
        else 
            $amount = @$this->amount;


        if (discount_fees(@$this->amount,@$this->discount_amount) - ( @$this->paid_amount ) ==0) {
            $payment_status = __('fees.paid');
        }else{
            $payment_status = '';
        }

        return [
            'id' => (int)@$this->id,
            'installment' => (string)@$this->installment->title,
            'amount' => (float)@$amount,
            'status' => (string)fees_payment_status(@$this->amount,@$this->discount_amount,@$this->paid_amount,@$this->active_status )[0],
            'due_date' => (string)@$this->due_date,
            'payment_ID' => (string)@$this->payment_ID,
            'mode' => (string)@$this->mode,
            'payment_date' => (string)@$this->payment_date,
            'discount' => (float)@$this->discount_amount,
            'paid' => (float)@$this->paid_amount,
            'balance' => (float)discount_fees(@$this->amount,@$this->discount_amount) - ( @$this->paid_amount ),
            'payment_status' => (string)@$payment_status,
            'payment_details' => FeesInstallmentDetailsResource::collection(@$this->payments),
        ];
    }
}
