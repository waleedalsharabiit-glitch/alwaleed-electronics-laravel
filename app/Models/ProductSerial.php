<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSerial extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'serial_number', 'status', 'notes'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // --- Scopes للحالة ---
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }
}