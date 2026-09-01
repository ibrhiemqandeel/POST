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

        // متوسط قيمة الطلب (Average Order Value) — مؤشر تجاري مهم
        $paidOrdersCount = Order::where('status', '!=', Order::STATUS_CANCELLED)->count();
        $stats['avg_order_value'] = $paidOrdersCount > 0
            ? round($stats['total_sales'] / $paidOrdersCount, 2)
            : 0.0;

        $recentOrders = Order::with('user')->latest()->take(6)->get();

        // المنتجات الأكثر مبيعاً (حسب مجموع الكميات المباعة في كل order_items)
        $bestSellers = OrderItem::selectRaw('product_id, product_name, SUM(quantity) as total_sold, SUM(price * quantity) as revenue')
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // تنبيهات المخزون — منتجات على وشك النفاد (1..5) أو نفدت فعلاً
        $lowStock = Product::where('stock', '>', 0)->where('stock', '<=', 5)
            ->orderBy('stock')->take(8)->get(['id', 'name', 'stock', 'price']);

        // سلسلة المبيعات لآخر 14 يوماً — للرسم البياني في الداشبورد.
        // تُحسب في PHP لتفادي اختلافات دوال التاريخ بين sqlite و mysql.
        $salesSeries = $this->salesSeries(14);

        // توزيع حالات الطلبات (لمخطط شريطي بسيط)
        $statusBreakdown = [
            ['label' => 'قيد الانتظار', 'value' => $stats['pending_orders'],    'class' => 'badge-pending'],
            ['label' => 'قيد المعالجة', 'value' => $stats['processing_orders'], 'class' => 'badge-info'],
            ['label' => 'تم التوصيل',   'value' => $stats['completed_orders'],  'class' => 'badge-synced'],
            ['label' => 'ملغي',         'value' => $stats['cancelled_orders'],  'class' => 'badge-out'],
        ];

        return view('dashboard', compact(
            'stats', 'recentOrders', 'bestSellers', 'lowStock', 'salesSeries', 'statusBreakdown'
        ));
    }

    /**
     * مبيعات آخر $days يوماً كسلسلة [ ['label'=>'d/m','date'=>'Y-m-d','total'=>float], ... ]
     * الطلبات غير الملغاة فقط.
     */
    protected function salesSeries(int $days): array
    {
        $start = now()->startOfDay()->subDays($days - 1);

        // نجلب مجاميع الطلبات اليومية ثم نطابقها مع كل يوم في المدى (بما فيها الأيام الصفرية)
        $orders = Order::where('status', '!=', Order::STATUS_CANCELLED)
            ->where('created_at', '>=', $start)
            ->get(['total', 'created_at']);

        $byDay = [];
        foreach ($orders as $order) {
            $key = $order->created_at->format('Y-m-d');
            $byDay[$key] = ($byDay[$key] ?? 0) + (float) $order->total;
        }

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->format('Y-m-d');
            $series[] = [
                'label' => $day->format('j/n'),
                'date'  => $key,
                'total' => round($byDay[$key] ?? 0, 2),
            ];
        }

        return $series;
    }
}
