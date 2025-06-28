<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'exchange_rate',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'exchange_rate' => 'decimal:6'
    ];

    /**
     * Scope a query to only include active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get only the active currencies.
     */
    public static function getActiveCurrencies()
    {
        return static::active()->get();
    }
}