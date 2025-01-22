<?php

namespace Modules\Wallet\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $casts = [
        'user_id' => 'integer',
        'school_id' => 'integer',
        'amount' => 'float',
        'bank_id' => 'integer',
        'expense' => 'float',
        'academic_id' => 'integer'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletTransactionFactory::new();
    }

    public function userName(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
