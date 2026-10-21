<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'customer_name',
        'customer_phone',
        'product_id',
        'quantity',
        'total_price',
        'deposit_amount',
        'remaining_amount',
        'expected_date',
        'status',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}