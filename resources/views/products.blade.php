@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="background-color: #101827; color: #f9fafb;">
        @if (session('error') || request('payment_status') == 'failed')
            <div class="alert alert-danger">❌ Payment failed. Please try again.</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="header position-relative text-center p-3" style="background-color: #101827;">
            <h2 class="text-primary m-0"><b>Products</b></h2>
            <span class="position-absolute top-0 end-0 m-3 fs-3">
                <a href="{{ route('cart') }}" style="color: #3b82f6;">🛒 (<span id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>)</a>
            </span>
        </div>

        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card product-card shadow-sm border-0 h-100" style="background-color: white; border-radius: 20px;">
                        <a href="{{ route('product.detail', $product->id) }}">
                            <img src="{{ $product->image }}" class="card-img-top p-3" alt="{{ $product->name }}"
                                 style="height: 300px; object-fit: contain; background: white; border-radius: 20px 20px 0 0;">
                        </a>
                        <div class="card-body text-center">
                            <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none; color: black;">
                                <p>{{ Str::limit($product->description, 50) }}</p>
                            </a>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center" style="border-top: 1px solid #374151; border-radius: 0 0 20px 20px; background-color: transparent;">
                            <a href="{{ route('product.detail', $product->id) }}" style="text-decoration: none; color: black;">
                                <span class="fw-bold">{{ $product->name }}</span>
                            </a>
                            <span class="text-primary fw-bold">₹ {{ $product->price }}</span>
                        </div>
                        <div class="p-3 text-center">
                            <a href="{{ route('add-cart', [$product->id]) }}" class="btn btn-success w-100" style="border-radius: 0 0 20px 20px;">Add To Cart</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        /* Product Card Hover Effect */
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            border-radius: 20px; /* Curved edges for the entire card */
            overflow: hidden;
            background-color: #1f2937; /* Initial background */
        }
        .product-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.4);
            background-color: #374151; /* Lighter background on hover */
        }

        /* Rounded Image */
        .product-card img {
            border-radius: 20px 20px 0 0;
        }

        /* Button Hover Effect */
        .btn-success {
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #22c55e; /* Lighter green on hover */
        }

        /* Card Footer & Add to Cart Button Transparency */
        .product-card .card-footer,
        .product-card .p-3 {
            background-color: transparent; /* Inherits the hover background */
        }

        /* Product link hover effect */
        .product-card a {
            transition: color 0.3s ease;
        }
        .product-card a:hover {
            color: #3b82f6 !important;
        }
    </style>
@endsection
