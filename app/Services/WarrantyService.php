<?php

namespace App\Services;

use App\Models\Warranty;

class WarrantyService
{
    public function getAllWarranties()
    {
        return Warranty::withCount('products')->latest()->get();
    }

    public function getActiveWarranties()
    {
        return Warranty::where('is_active', true)->get();
    }

    public function createWarranty(array $data): Warranty
    {
        return Warranty::create($data);
    }

    public function updateWarranty(Warranty $warranty, array $data): Warranty
    {
        $warranty->update($data);
        return $warranty;
    }

    public function deleteWarranty(Warranty $warranty): bool
    {
        return $warranty->delete();
    }
}