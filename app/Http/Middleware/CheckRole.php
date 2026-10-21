<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login.show');
        }

        $user = Auth::user();

        // التأكد من أن الحساب نشط
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.show')->withErrors([
                'email' => 'حسابك معطل حالياً. يرجى التواصل مع الإدارة.',
            ]);
        }

        // التحقق من صلاحية المستخدم
        if (!in_array($user->role, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}