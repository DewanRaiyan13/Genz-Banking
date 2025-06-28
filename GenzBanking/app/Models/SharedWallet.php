<?php

// app/Models/SharedWallet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedWallet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'creator_id', 'balance', 'description'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'shared_wallet_members')
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(SharedWalletTransaction::class);
    }
}


