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

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
