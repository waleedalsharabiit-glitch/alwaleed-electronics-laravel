<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'duration_months', 'terms'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}