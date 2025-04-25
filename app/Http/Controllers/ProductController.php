<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(){
        return view('products')->with('products', Product::withCount('reviews')->get());
    }

    public function cart()
{
    if (Auth::check()) {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        $cart = [];

        foreach ($cartItems as $item) {
            $cart[$item->product->id] = [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'image' => $item->product->image
            ];
        }

        session()->put('cart', $cart);
    }

    return view('cart');
}

public function addToCart(Product $product)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity']++;
    } else {
        $cart[$product->id] = [
            'name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
            'image' => $product->image
        ];
    }

    session()->put('cart', $cart);

    if (Auth::check()) {
        CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $product->id],
            ['quantity' => $cart[$product->id]['quantity']]
        );
    }

    return redirect()->back()->with('success', "{$product->name} added to cart!");
}

public function removeFromCart($id)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $id)->delete();
        }
    }

    return redirect()->back()->with('success', "Removed from Cart");
}


public function updateCart(Request $request, $id)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        if ($request->input('action') == 'increase') {
            $cart[$id]['quantity']++;
        } elseif ($request->input('action') == 'decrease' && $cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        }
        session()->put('cart', $cart);

        if (Auth::check()) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $id],
                ['quantity' => $cart[$id]['quantity']]
            );
        }
    }

    return redirect()->back()->with('success', 'Cart updated successfully!');
}


}

