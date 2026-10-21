<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['order_number', 'customer_name', 'customer_phone', 'total_amount', 'payment_status', 'order_status', 'notes'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}