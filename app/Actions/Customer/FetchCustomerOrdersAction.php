<?php

namespace App\Actions\Customer;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchCustomerOrdersAction
{
    public function execute(User $user): LengthAwarePaginator
    {
        return Order::with([
            'orderItems.product.warranty',
            'orderItems.productSerial'
        ])
        ->where('user_id', $user->id)
        ->orWhere('customer_phone', $user->phone)
        ->latest()
        ->paginate(10);
    }
}