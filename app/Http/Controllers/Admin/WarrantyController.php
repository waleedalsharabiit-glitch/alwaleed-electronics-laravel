<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WarrantyRequest;
use App\Models\Warranty;
use App\Services\WarrantyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function __construct(protected WarrantyService $warrantyService) {}

    public function index(): View
    {
        $warranties = $this->warrantyService->getAllWarranties();
        return view('Admin.warranties.index', compact('warranties'));
    }

    public function store(WarrantyRequest $request): RedirectResponse
    {
        $this->warrantyService->createWarranty($request->validated());

        return redirect()->route('admin.warranties.index')->with('success', 'تم إضافة سياسة الضمان بنجاح.');
    }

    public function update(WarrantyRequest $request, Warranty $warranty): RedirectResponse
    {
        $this->warrantyService->updateWarranty($warranty, $request->validated());

        return redirect()->route('admin.warranties.index')->with('success', 'تم تحديث بيانات الضمان بنجاح.');
    }

    public function destroy(Warranty $warranty): RedirectResponse
    {
        $this->warrantyService->deleteWarranty($warranty);

        return redirect()->route('admin.warranties.index')->with('success', 'تم حذف وثيقة الضمان بنجاح.');
    }
}