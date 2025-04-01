<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(){
        return view('products')->with('products', Product::all());
    }

    public function cart(){
        return view('cart');
    }

    public function addToCart(Product $product){
        $cart = session()->get('cart');
        if(!$cart){
            $cart = [
                $product->id => [
                    'name' => $product->name,
                    'quantity' => '1',
                    'price' => $product->price,
                    'image' => $product->image
                ]
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', "{$product->name} added to cart!");

        }

        if(isset($cart[$product->id])){
            $cart[$product->id]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', "{$product->name} added to cart!");

        }

        $cart[$product->id] = [
            'name' => $product->name,
            'quantity' => '1',
            'price' => $product->price,
            'image' => $product->image
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', "{$product->name} added to cart!");

    }

    public function removeFromCart($id){
        $cart = session()->get('cart');
        if(isset($cart[$id])){
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('Success', "Remove from Cart");
    }

    public function updateCart(Request $request, $id)
{
    $cart = session()->get('cart');

    if ($cart && isset($cart[$id])) {
        if ($request->input('action') == 'increase') {
            $cart[$id]['quantity']++;
        } elseif ($request->input('action') == 'decrease' && $cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        }
        session()->put('cart', $cart);
    }

    return redirect()->back()->with('success', 'Cart updated successfully!');
}

}

