<?php

namespace App\Http\Resources\v2\Admin\BankPayment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankPaymentListResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->transcationDetails->map(function ($transaction) {
            return [
                'fees_type' => @$transaction->transcationFeesType->name,
                'paid_amount' => @$transaction->paid_amount,
                'created_at' => @$transaction->created_at,
                'note' => @$transaction->note,
            ];
        });

        return [
            'transaction_id'    => (int)@$this->id,
            'student_name'      => (string)@$this->feeStudentInfo->full_name,
            'fees_type'         => (string)@$this['fees_type'],
            'paid_amount'       => (float)@$this['paid_amount'],
            'amount'            => (float)@$this['paid_amount'],
            'date'              => (string)date('jS M, Y', strtotime(@$this['created_at'])),
            'note'              => (string)@$this['note'],
            'file'              => @$this->file ? (string)asset($this->file) : null,
            'status'            => (string)strtoupper(@$this->paid_status)
        ];
    }
}
