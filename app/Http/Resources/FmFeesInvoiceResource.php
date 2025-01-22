<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFeesInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $balance = $this->Tamount + $this->Tfine - ($this->Tpaidamount + $this->Tweaver);
        if ($balance == 0) {
            $status = 'Paid';
        } else {
            if ($this->Tpaidamount > 0) {
                $status = 'Partial';
            } else {
                $status = 'Unpaid';
            }
        }

        return [
            'id'            => (int)$this->id,
            'full_name'     => (string)$this->studentInfo->full_name,
            'class'         => (string)$this->recordDetail->class->class_name,
            'section'       => (string)$this->recordDetail->section->section_name,
            'amount'        => (float)$this->Tamount ?? (float)0,
            'waiver'        => (float)$this->Tweaver ?? (float)0,
            'fine'          => (float)$this->Tfine ?? (float)0,
            'paid_amount'   => (float)$this->Tpaidamount ?? (float)0,
            'balance'       => (float)$balance ?? (float)0,
            'status'        => (string)$status,
            'create_date'   => (string)$this->create_date,
        ];
    }
}
