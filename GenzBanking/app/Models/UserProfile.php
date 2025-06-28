<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'document_path',
        'password', 'security_question', 'security_answer',
        'balance', 'reward_points', 'travel_quota', 'rp_validity', 'tq_validity'
    ];

    protected $hidden = ['password', 'security_answer'];
}

