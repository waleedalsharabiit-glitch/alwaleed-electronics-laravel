<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SerialRequest;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Services\SerialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SerialController extends Controller
{
    public function __construct(protected SerialService $serialService) {}

    public function index(Request $request): View
    {
        $products = Product::select(['id', 'name', 'sku'])->get();
        $serials  = $this->serialService->getFilteredSerials($request->query('product_id'), $request->query('status'));

        return view('Admin.serials.index', compact('serials', 'products'));
    }

    public function store(SerialRequest $request): RedirectResponse
    {
        $count = $this->serialService->storeSerials($request->validated());

        return redirect()->route('admin.serials.index')
            ->with('success', "تم تسجيل {$count} سيريال جديد وتحديث رصيد المخزن بنجاح.");
    }

    public function updateStatus(Request $request, ProductSerial $serial): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:available,sold,maintenance,damaged',
            'notes'  => 'nullable|string',
        ]);

        $this->serialService->updateStatus($serial, $validated['status'], $validated['notes'] ?? null);

        return redirect()->route('admin.serials.index')->with('success', 'تم تحديث حالة السيريال والمخزن.');
    }

    public function destroy(ProductSerial $serial): RedirectResponse
    {
        $this->serialService->deleteSerial($serial);

        return redirect()->route('admin.serials.index')->with('success', 'تم حذف الرقم التسلسلي وإعادة احتساب المخزون.');
    }
}