<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'symbol',
        'type',
        'quantity',
        'price',
        'total_amount',
    ];

    /**
     * Calculate total amount for this transaction
     */
    public static function calculateTotal(float $quantity, float $price): float
    {
        return $quantity * $price;
    }

    /**
     * Scope to get buy transactions
     */
    public function scopeBuys($query)
    {
        return $query->where('type', 'buy');
    }

    /**
     * Scope to get sell transactions
     */
    public function scopeSells($query)
    {
        return $query->where('type', 'sell');
    }

    /**
     * Scope to get transactions for a User
     */
    public function scopeForUser($query, string $UserId)
    {
        return $query->where('user_id', $UserId);
    }
}
