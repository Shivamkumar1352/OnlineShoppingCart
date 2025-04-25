<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;


class RazorpayController extends Controller
{

    public function payment(Request $request) {
        $amount = $request->input('amount'); // Retrieve amount from form input

        // Debug API credentials
        $key = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');

        if (!$key || !$secret) {
            return response()->json(['error' => 'Razorpay Key or Secret is missing!']);
        }

        if (!$amount || $amount <= 0) {
            return response()->json(['error' => 'Invalid amount']);
        }

        $api = new Api($key, $secret);

        try {
            $orderData = [
                'receipt' => 'order_' . rand(1000, 9999),
                'amount' => $amount * 100, // Convert to paise
                'currency' => 'INR',
                'payment_capture' => 1
            ];

            $order = $api->order->create($orderData);
            return view('payment', ['orderId' => $order["id"], 'amount' => $amount * 100]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        \Log::info('Razorpay callback received', $request->all());

        try {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            \Log::info('Verifying payment signature');
            $api->utility->verifyPaymentSignature($attributes);

            \Log::info('Payment verified, creating order');
            $order = $this->createOrder($request);

            \Log::info('Order created', ['order_id' => $order->id]);

            // Clear cart
            session()->forget('cart');
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            }

            return response()->json([
                'success' => true,
                'redirect' => route('profile.orders'),
                'message' => 'Order placed successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment processing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 400);
        }
    }
    protected function createOrder($request)
{
    if (!session('cart')) {
        throw new \Exception('Cart is empty');
    }

    $total = 0;
    $items = [];

    foreach (session('cart') as $id => $item) {
        $total += $item['price'] * $item['quantity'];
        $items[] = [
            'product_id' => $id,
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ];
    }

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'completed',
            'payment_id' => $request->razorpay_payment_id,
            'payment_method' => 'razorpay',
        ]);

        $order->items()->createMany($items);

        DB::commit();
        return $order;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
}
