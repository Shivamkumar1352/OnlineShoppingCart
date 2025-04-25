<!-- resources/views/product-detail.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4" style="background-color: #1f2937; color: #f9fafb; border-radius: 10px; padding: 20px;">
    <!-- Product Details Section -->
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <img src="{{ $product->image }}" class="img-fluid rounded shadow" alt="{{ $product->name }}"
                 style="max-height: 500px; width: 100%; object-fit: contain; background: #374151; padding: 10px;">
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <h1 class="mb-3" style="color: #3b82f6;">{{ $product->name }}</h1>

            <!-- Rating -->
            <div class="d-flex align-items-center mb-3">
                <div class="rating-stars" style="color: #f59e0b;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->averageRating()))
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <span class="ms-2">{{ number_format($product->averageRating(), 1) }} ({{ $product->reviews->count() }} reviews)</span>
            </div>

            <h3 class="mb-4">₹ {{ number_format($product->price, 2) }}</h3>

            <div class="mb-4">
                <p>{{ $product->description }}</p>
            </div>

            <div class="d-flex gap-2">
                <form action="{{ route('add-cart', $product) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
                <a href="{{ route('products') }}" class="btn btn-primary">Back to Products</a>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-5">
        <h3 style="color: #3b82f6;">Customer Reviews</h3>

        <!-- Review Form -->
        @auth
        <div class="card mb-4" style="background-color: #374151; border: none;">
            <div class="card-body text-white">
                <h5 class="text-white">Write a Review</h5>
                <form action="{{ route('products.reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="rating" class="form-label text-white">Rating</label>
                        <select class="form-select text-white" id="rating" name="rating" required style="background-color: #1f2937; color: #f9fafb; border: 1px solid #4b5563;">
                            <option value="">Select rating</option>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label text-white">Review</label>
                        <textarea class="form-control text-white" id="comment" name="comment" rows="3" required style="background-color: #1f2937; color: #f9fafb; border: 1px solid #4b5563;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-info text-white" style="background-color: #374151; border-color: #4b5563;">
            Please <a href="{{ route('login') }}" class="alert-link text-white">login</a> to leave a review.
        </div>
        @endauth

        <!-- Reviews List -->
        @if($product->reviews->isEmpty())
            <div class="alert alert-secondary text-white" style="background-color: #374151; border-color: #4b5563;">No reviews yet. Be the first to review!</div>
        @else
            <div class="list-group">
                @foreach($product->reviews as $review)
                <div class="list-group-item mb-3 text-white" style="background-color: #374151; border: 1px solid #4b5563; border-radius: 5px;">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1 text-white">{{ $review->user->name }}</h5>
                        <small class="text-white-50">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mb-1" style="color: #f59e0b;">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <p class="mb-1 text-white">{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
