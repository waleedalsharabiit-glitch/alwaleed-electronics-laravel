<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PreOrderRequest;
use App\Models\PreOrder;
use App\Models\Product;
use App\Services\PreOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PreOrderController extends Controller
{
    public function __construct(protected PreOrderService $preOrderService) {}

    public function index(Request $request): View
    {
        $preOrders = $this->preOrderService->getAllPreOrders($request->query('status'));
        $products  = Product::select(['id', 'name', 'price', 'sku'])->get();

        return view('Admin.pre_orders.index', compact('preOrders', 'products'));
    }

    public function store(PreOrderRequest $request): RedirectResponse
    {
        $this->preOrderService->createPreOrder($request->validated());

        return redirect()->route('admin.pre-orders.index')
            ->with('success', 'تم تسجيل طلب الحجز المسبق وإنشاء رمز التتبع بنجاح.');
    }

    public function updateStatus(Request $request, PreOrder $preOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,arrived,delivered,cancelled',
        ]);

        $this->preOrderService->updateStatus($preOrder, $validated['status']);

        return redirect()->route('admin.pre-orders.index')
            ->with('success', 'تم تحديث حالة طلب الحجز المسبق.');
    }

    public function destroy(PreOrder $preOrder): RedirectResponse
    {
        $this->preOrderService->deletePreOrder($preOrder);

        return redirect()->route('admin.pre-orders.index')
            ->with('success', 'تم إلغاء وحذف طلب الحجز المسبق.');
    }
}