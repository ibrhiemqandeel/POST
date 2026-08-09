<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للوحة تحكم الأدمن مع إحصائيات عامة
     */
    public function index()
    {
        // إحصائيات سريعة للعارضات في لوحة التحكم
        $stats = [
            'total_users'      => User::count(),
            'total_products'   => Product::count(),
            'total_orders'     => Order::count(),
            'total_categories' => Category::count(),
        ];

        // أحدث 5 الطلبات المسجلة
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // ملاحظة: الملف resources/views/dashboard.blade.php هو نفسه واجهة
        // إدارة المنتجات المخصصة للأدمن (وليست صفحة "حسابي" للمستخدم العادي)،
        // لذلك لوحة التحكم الإدارية تستخدمه مباشرة بدل إنشاء ملف مكرر.
        return view('dashboard', compact('stats', 'recentOrders'));
    }
}
