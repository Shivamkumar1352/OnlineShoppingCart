@extends('layouts.app')

@section('content')
<div class="container mt-4 order-history-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="order-history-title"><i class="bi bi-receipt"></i> Order History</h2>
        <a href="{{ route('products') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
    </div>

    @forelse($orders as $order)
        <div class="card mb-4 order-card">
            <div class="card-header order-card-header">
                <div class="order-header-info">
                    <span class="order-id">Order #{{ $order->id }}</span>
                    <span class="order-status badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="order-date">
                    {{ $order->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7 order-items-col">
                        <h5 class="section-title">Items</h5>
                        <ul class="list-group order-items-list">
                            @foreach($order->items as $item)
                            <li class="list-group-item order-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($item->product)
                                        <img src="{{ $item->product->image }}" class="order-item-image">
                                        <div class="order-item-details">
                                            <h6 class="order-item-name">{{ $item->product->name }}</h6>
                                            <small class="order-item-price">₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}</small>
                                            @if($item->discount > 0)
                                                <small class="order-item-discount text-success">
                                                    (₹{{ number_format($item->discount, 2) }} off)
                                                </small>
                                            @endif
                                        </div>
                                        @else
                                        <span class="deleted-product">Product not available</span>
                                        @endif
                                    </div>
                                    <span class="order-item-total">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-5 order-summary-col">
                        <div class="card order-summary-card">
                            <div class="card-body">
                                <h5 class="summary-title">Order Summary</h5>
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span>₹{{ number_format($order->total, 2) }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Payment Method:</span>
                                    <span>{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Payment ID:</span>
                                    <span class="payment-id">{{ $order->payment_id }}</span>
                                </div>
                                <hr class="summary-divider">
                                <div class="summary-row total-row">
                                    <span>Total:</span>
                                    <span>₹{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-orders alert text-center">
            <i class="bi bi-cart-x empty-order-icon"></i>
            <h4 class="empty-order-title">No orders found</h4>
            <p class="empty-order-text">You haven't placed any orders yet.</p>
            <a href="{{ route('products') }}" class="btn btn-primary empty-order-btn">Start Shopping</a>
        </div>
    @endforelse
</div>

<style>
    /* Base Container */
    .order-history-container {
        background-color: #1f2937;
        color: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
    }

    /* Titles */
    .order-history-title {
        color: #3b82f6;
        font-weight: 600;
    }
    .section-title, .summary-title {
        color: #e5e7eb;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    /* Order Card */
    .order-card {
        background-color: #374151;
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .order-card-header {
        background-color: #4b5563;
        border-bottom: 1px solid #6b7280;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .order-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .order-id {
        font-weight: 600;
        color: #f3f4f6;
    }
    .order-date {
        color: #d1d5db;
        font-size: 0.9rem;
    }

    /* Order Items */
    .order-items-list {
        border-radius: 6px;
        overflow: hidden;
    }
    .order-item {
        background-color: #4b5563;
        border-color: #6b7280;
        padding: 1rem;
        margin-bottom: 8px;
        border-radius: 6px;
    }
    .order-item:hover {
        background-color: #525c6d;
    }
    .order-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 15px;
    }
    .order-item-name {
        color: #f9fafb;
        margin-bottom: 4px;
    }
    .order-item-price {
        color: #d1d5db;
    }
    .order-item-total {
        color: #f9fafb;
        font-weight: 500;
    }
    .deleted-product {
        color: #9ca3af;
        font-style: italic;
    }

    /* Order Summary */
    .order-summary-card {
        background-color: #4b5563;
        border: none;
        border-radius: 8px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #e5e7eb;
    }
    .summary-divider {
        border-color: #6b7280;
        margin: 1rem 0;
    }
    .total-row {
        font-weight: 600;
        color: #f9fafb;
        font-size: 1.1rem;
    }
    .payment-id {
        word-break: break-all;
        max-width: 150px;
        display: inline-block;
        vertical-align: middle;
    }

    /* Empty State */
    .empty-orders {
        background-color: #374151;
        padding: 2rem;
        border-radius: 8px;
    }
    .empty-order-icon {
        font-size: 3rem;
        color: #9ca3af;
    }
    .empty-order-title {
        color: #f3f4f6;
        margin-top: 1rem;
    }
    .empty-order-text {
        color: #d1d5db;
    }
    .empty-order-btn {
        margin-top: 1rem;
        padding: 8px 20px;
    }

    /* Status Badges */
    .badge.bg-success {
        background-color: #10b981 !important;
    }
    .badge.bg-warning {
        background-color: #f59e0b !important;
        color: #1f2937 !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .order-items-col, .order-summary-col {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }
        .order-summary-col {
            margin-top: 1.5rem;
        }
        .order-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>
@endsection
