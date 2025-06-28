<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardOffer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'annual_fee', 'interest_rate'];
}
