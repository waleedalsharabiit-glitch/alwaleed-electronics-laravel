<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'product_serial_id', 'quantity', 'unit_price', 'subtotal'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function serial(): BelongsTo { return $this->belongsTo(ProductSerial::class, 'product_serial_id'); }
}