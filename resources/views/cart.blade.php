@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="background-color: #1f2937; color: #f9fafb;">
        <h2 class="text-center mb-4" style="color: #3b82f6;"><b>🛒 Your Cart</b></h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <table class="table table-hover table-bordered shadow-sm" style="background-color: #1f2937; color: #f9fafb;">
                    <thead style="background-color: #3b82f6; color: #ffffff;">
                        <tr>
                            <th width="40%">Product</th>
                            <th width="10%">Price</th>
                            <th width="20%">Quantity</th>
                            <th width="15%">Sub Total</th>
                            <th width="15%">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @if (session('cart'))
                            @foreach (session('cart') as $id => $product)
                                @php
                                    $sub_total = $product['price'] * $product['quantity'];
                                    $total += $sub_total;
                                @endphp
                                <tr style="background-color: #1f2937; color: #f9fafb;">
                                    <td class="d-flex align-items-center">
                                        <img src="{{ $product['image'] }}" class="rounded me-2" height="100" alt="Product" style="background: #374151; padding: 5px; border-radius: 10px;">
                                        <span>{{ $product['name'] }}</span>
                                    </td>
                                    <td>₹ {{ $product['price'] }}</td>
                                    <td>
                                        <form action="{{ route('update-cart', $id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            <button type="submit" name="action" value="decrease" class="btn btn-outline-dark btn-sm me-2">-</button>
                                            <span class="fw-bold">{{ $product['quantity'] }}</span>
                                            <button type="submit" name="action" value="increase" class="btn btn-outline-dark btn-sm ms-2">+</button>
                                        </form>
                                    </td>
                                    <td>₹ {{ $sub_total }}</td>
                                    <td>
                                        <a href="{{ route('remove', $id) }}" class="btn btn-danger btn-sm">X</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot style="background-color: #1f2937; color: #f9fafb;">
                        <tr>
                            <td>
                                <a href="{{ route('products') }}" class="btn btn-outline-primary">⬅ Continue Shopping</a>
                            </td>
                            <td colspan="2"></td>
                            <td><strong>Total: ₹ {{ $total }}</strong></td>
                            <td>
                                @if($total > 0)
                                    <form action="{{ route('razorpay.payment') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $total }}">
                                        <button type="submit" class="btn btn-success">🛒 Pay Now</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection
