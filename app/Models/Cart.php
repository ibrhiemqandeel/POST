<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * إجمالي عدد القطع في السلة (مجموع الكميات).
     */
    public function count(): int
    {
        return (int) $this->items->sum('quantity');
    }

    /**
     * إجمالي سعر السلة بناءً على السعر المخزّن وقت الإضافة (price snapshot).
     */
    public function total(): float
    {
        return (float) $this->items->sum(fn (CartItem $item) => $item->price * $item->quantity);
    }

    /**
     * إحضار سلة المستخدم الحالي (مسجل أو زائر) أو إنشاؤها إذا لم توجد.
     * للزوار غير المسجلين تُخزَّن هوية السلة في الـ session حتى لا تضيع
     * أثناء التصفح، تماماً كما كانت السلة الوهمية تعمل سابقاً عبر localStorage.
     */
    public static function current(): self
    {
        if (auth()->check()) {
            return static::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = session('cart_session_id');

        if (! $sessionId) {
            $sessionId = (string) Str::uuid();
            session(['cart_session_id' => $sessionId]);
        }

        return static::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    /**
     * دمج سلة الزائر (المخزّنة عبر session) في سلة المستخدم بعد تسجيل الدخول،
     * حتى لا تضيع المنتجات التي أضافها قبل الدخول. تُستدعى مباشرةً بعد
     * Auth::login في تسجيل الدخول العادي و Google.
     */
    public static function mergeGuestCartIntoUser(): void
    {
        if (! auth()->check()) {
            return;
        }

        $sessionId = session('cart_session_id');
        if (! $sessionId) {
            return;
        }

        $guestCart = static::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->with('items')
            ->first();

        // لا توجد سلة زائر فعلية للدمج
        if (! $guestCart || $guestCart->items->isEmpty()) {
            session()->forget('cart_session_id');
            return;
        }

        $userCart = static::firstOrCreate(['user_id' => auth()->id()]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existing) {
                // جمع الكميات مع احترام سقف الكمية (99) المستخدم في السلة
                $existing->update([
                    'quantity' => min(99, $existing->quantity + $guestItem->quantity),
                ]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity'   => $guestItem->quantity,
                    'price'      => $guestItem->price,
                ]);
            }
        }

        // تنظيف سلة الزائر بعد نقل عناصرها + إزالة معرّف الجلسة
        $guestCart->items()->delete();
        $guestCart->delete();
        session()->forget('cart_session_id');
    }

    /**
     * نسخة خفيفة لا تُنشئ سلة جديدة في قاعدة البيانات — تُستخدم فقط لعرض
     * عدّاد السلة في الهيدر بدون توليد صفوف فارغة لكل زائر يفتح الموقع.
     */
    public static function currentCount(): int
    {
        $query = static::query();

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $sessionId = session('cart_session_id');
            if (! $sessionId) {
                return 0;
            }
            $query->where('session_id', $sessionId);
        }

        $cart = $query->first();

        return $cart ? (int) $cart->items()->sum('quantity') : 0;
    }
}
