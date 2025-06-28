<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportEndorsement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'passport_number',
        'amount_usd',
        'status',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}