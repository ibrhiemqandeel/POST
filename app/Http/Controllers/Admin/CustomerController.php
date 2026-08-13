<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')->orderByDesc('created_at');

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $customers = $query->get()->map(function (User $user) {
            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'is_admin'     => (bool) $user->is_admin,
                'orders_count' => $user->orders_count,
                'joined'       => $user->created_at->format('Y-m-d'),
            ];
        });

        if (! $request->wantsJson()) {
            return view('admin.customers');
        }

        return response()->json([
            'success'   => true,
            'customers' => $customers,
        ]);
    }

    public function show(User $user): JsonResponse
    {
        $orders = $user->orders()->with('items')->latest()->get()->map(fn ($order) => [
            'id'     => $order->id,
            'total'  => (float) $order->total,
            'status' => $order->status,
            'date'   => $order->created_at->format('Y-m-d H:i'),
        ]);

        return response()->json([
            'success'  => true,
            'customer' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'is_admin' => (bool) $user->is_admin,
                'joined'   => $user->created_at->format('Y-m-d'),
            ],
            'orders' => $orders,
        ]);
    }
}
