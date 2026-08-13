<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للوحة تحكم الأدمن مع إحصائيات عامة حقيقية
     * (وليست أرقاماً وهمية) — كل قيمة هنا تُحسب مباشرة من قاعدة البيانات.
     */
    public function index()
    {
        $stats = [
            'total_products'     => Product::count(),
            'active_products'    => Product::where('stock', '>', 0)->count(),
            'out_of_stock'       => Product::where('stock', '<=', 0)->count(),

            'total_orders'       => Order::count(),
            'pending_orders'     => Order::where('status', Order::STATUS_PENDING)->count(),
            'processing_orders'  => Order::whereIn('status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])->count(),
            'completed_orders'   => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled_orders'   => Order::where('status', Order::STATUS_CANCELLED)->count(),

            'total_customers'    => User::where('is_admin', false)->count(),
            'total_categories'   => Category::count(),

            // إجمالي المبيعات الفعلي = مجموع الطلبات غير الملغاة فقط
            'total_sales'        => (float) Order::where('status', '!=', Order::STATUS_CANCELLED)->sum('total'),
        ];

        // للتوافق مع أي كود قديم كان يعتمد على total_users
        $stats['total_users'] = User::count();

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // المنتجات الأكثر مبيعاً (حسب مجموع الكميات المباعة في كل order_items)
        $bestSellers = OrderItem::selectRaw('product_id, product_name, SUM(quantity) as total_sold')
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // ملاحظة: الملف resources/views/dashboard.blade.php هو نفسه واجهة
        // إدارة المنتجات المخصصة للأدمن (وليست صفحة "حسابي" للمستخدم العادي)،
        // لذلك لوحة التحكم الإدارية تستخدمه مباشرة بدل إنشاء ملف مكرر.
        return view('dashboard', compact('stats', 'recentOrders', 'bestSellers'));
    }
}
