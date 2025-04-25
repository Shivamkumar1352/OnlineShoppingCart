<?php

namespace App\Http\Controllers;

use App\Models\Order; // You'll need to create this model
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function orders()
{
    $orders = auth()->user()->orders()
                ->with(['items' => function($query) {
                    $query->with(['product' => function($q) {
                        $q->withTrashed(); // Now works because Product uses SoftDeletes
                    }]);
                }])
                ->latest()
                ->get();

    return view('profile.orders', [
        'orders' => $orders,
        'success' => session('success')
    ]);
}
}
