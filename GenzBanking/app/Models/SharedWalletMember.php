<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedWalletMember extends Model
{
    use HasFactory;

    protected $fillable = ['shared_wallet_id', 'user_id'];

    public function sharedWallet()
    {
        return $this->belongsTo(SharedWallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

