<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ATM extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_place_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'distance',
        'user_id' // To track who saved/favorited
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
