<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

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

    // public function callback(Request $request){
    //     $request->validate([
    //         'razorpay_payment_id' => 'required|string',
    //         'razorpay_order_id' => 'required|string',
    //         'razorpay_signature' => 'required|string',
    //     ]);

    //     $key = env('RAZORPAY_KEY');
    //     $secret = env('RAZORPAY_SECRET');
    //     $api = new Api($key, $secret);

    //     try {
    //         $attributes = [
    //             'razorpay_order_id' => $request->razorpay_order_id,
    //             'razorpay_payment_id' => $request->razorpay_payment_id,
    //             'razorpay_signature' => $request->razorpay_signature,
    //         ];

    //         $api->utility->verifyPaymentSignature($attributes);

    //         // Store success message in session
    //         session()->flash('success', '🎉 Your order has been placed successfully!');

    //         // Clear cart after successful payment
    //         session()->forget('cart');

    //         return response()->json(['success' => true]);

    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
    //     }
    // }
    public function callback(Request $request){
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $key = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');
        $api = new Api($key, $secret);

        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Store success message in session
            session()->flash('success', '🎉 Your order has been placed successfully!');

            // Clear cart after successful payment
            session()->forget('cart');

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Store failure message in session
            session()->flash('error', '❌ Payment failed. Please try again.');

            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

}
