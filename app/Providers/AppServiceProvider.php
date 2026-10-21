<?php
namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // صلاحية إدارة المنتجات الكاملة (الإنشاء والتعديل والحذف)
        Gate::define('manage-products', function (User $user) {
            return in_array($user->role, ['admin', 'employee'], true);
        });

        // صلاحية الحذف الحساسة (Admin فقط)
        Gate::define('delete-records', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
