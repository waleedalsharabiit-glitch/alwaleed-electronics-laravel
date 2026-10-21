<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\FetchCustomerOrdersAction;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class CustomerDashboardController extends Controller
{
    public function index(Request $request, FetchCustomerOrdersAction $action): View
    {
        $user=Auth::user();
        $orders = $action->execute($request->user());
        return view('store.customer.dashboard', compact('orders','user'));
    }

    public function showOrder(Request $request, int $id): View
    {
        $user = $request->user();

        $order = Order::with([
            'orderItems.product.warranty',
            'orderItems.productSerial',
        ])
        ->where('id', $id)
        ->where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
            
            if (!empty($user->phone)) {
                $query->orWhere('customer_phone', $user->phone);
            }
        })
        ->firstOrFail();

        return view('store.customer.order-details', compact('order'));
    }
}