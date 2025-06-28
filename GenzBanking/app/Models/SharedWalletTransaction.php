<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['shared_wallet_id', 'user_id', 'amount', 'type', 'note'];

    public function sharedWallet()
    {
        return $this->belongsTo(SharedWallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

