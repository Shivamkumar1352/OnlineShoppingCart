
@extends('layouts.app')

@section('content')


    <div class="container mt-4">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="header position-relative text-center p-3" style="background-color: #f8f9fa;">
            <h2 class="text-primary m-0"><b>🛍️ Product Page</b></h2>
            <span class="position-absolute top-0 end-0 m-3 fs-3">
                <a href="{{ route('cart') }}">🛒 (<span id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>)</a>
            </span>
        </div>

        <div class="row">
            @if (count($products) > 0)
                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <!-- Product Image -->
                            <img src="{{ $product->image }}" class="card-img-top p-3" alt="{{ $product->name }}" style="height: 300px; object-fit: contain; background: #f8f8f8; border-radius: 10px;">

                            <div class="card-body text-center">
                                <p class="text-muted">{{ $product->description }}</p>
                            </div>

                            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                <span class="fw-bold">{{ $product->name }}</span>
                                <span class="text-primary fw-bold">₹ {{ $product->price }}</span>
                            </div>

                            <div class="p-3 text-center">
                                <a href="{{ route('add-cart', [$product->id]) }}" class="btn btn-success w-100">Add To Cart</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
