<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\Bundle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // توجيه ذكي حسب الصلاحية
            if (in_array($user->role, ['admin', 'employee'])) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'بيانات الاعتماد المدخلة غير صحيحة.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // الموديل يتولى التشفير تلقائياً عبر Hashed Cast
            'role'     => 'user', // التسجيل الجديد دائماً يمنح صلاحية زبون
        ]);

        Auth::login($user);

        // توجيه المستخدم الجديد مباشرة إلى لوحة حسابه
        return redirect()->route('dashboard');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * التوجيه الذكي المركزي عند الدخول على /dashboard
     */
    public function dashboard(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (in_array($user->role, ['admin', 'employee'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    /**
     * عرض لوحة تحكم الإدارة الشاملة
     */
    public function adminDashboard(): View
    {
        $stats = [
            'total_products'    => Product::count(),
            'available_serials' => ProductSerial::where('status', 'available')->count(),
            'total_sales'       => Order::sum('total_amount'),
            'total_orders'      => Order::count(),
            'active_preorders'  => PreOrder::whereIn('status', ['pending', 'confirmed'])->count(),
            'active_bundles'    => Bundle::where('is_active', true)->count(),
        ];

        $latestOrders = Order::latest()->take(5)->get();
        $recentPreOrders = PreOrder::with('product')->latest()->take(5)->get();

        return view('Admin.dashboard', compact('stats', 'latestOrders', 'recentPreOrders'));
    }
}