<?php

namespace App\Http\Resources\v2;

use App\SmPaymentMethhod;
use Illuminate\Http\Request;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->status == 'pending') {
            $status = __('common.pending');
        } elseif ($this->type == 'diposit' && $this->status == 'approve') {
            $status = __('wallet::wallet.approve');
        } elseif ($this->status == 'reject') {
            $status = __('wallet::wallet.reject');
        } elseif ($this->type == 'refund' && $this->status == 'approve') {
            $status = __('wallet::wallet.refund');
        }

        $method = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $this->payment_method)
            ->first();
    
        $paymentMethod = $method ? $method->method : $this->payment_method;

        return [
            'id'             => (int)$this->id,
            'created_at'     => (string)$this->created_at,
            'payment_method' => (string)$paymentMethod,
            'amount'         => (float)$this->amount,
            'status'         => (string)$status,
        ];
    }
}
