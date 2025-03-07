@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="section-title">Checkout</h2>
    </div>
</div>

<form action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <!-- Delivery Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Delivery Address</h5>
                </div>
                <div class="card-body">
                    @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 {{ $address->is_default ? 'border-primary' : 'border' }}">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_uuid" id="address-{{ $address->uuid }}" value="{{ $address->uuid }}" {{ $address->is_default ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="address-{{ $address->uuid }}">
                                                    <strong>{{ $address->title }}</strong>
                                                </label>
                                                @if($address->is_default)
                                                    <span class="badge bg-primary ms-2">Default</span>
                                                @endif
                                            </div>
                                            <div class="mt-2 ms-4">
                                                <p class="mb-1">{{ $address->address_line1 }}</p>
                                                @if($address->address_line2)
                                                    <p class="mb-1">{{ $address->address_line2 }}</p>
                                                @endif
                                                <p class="mb-1">{{ $address->district }}, {{ $address->city }}</p>
                                                <p class="mb-0">{{ $address->country }} {{ $address->postal_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Add New Address
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i> You need to add a delivery address to continue.
                        </div>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Delivery Address
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment-cash" value="cash" checked required>
                        <label class="form-check-label" for="payment-cash">
                            <i class="bi bi-cash text-success"></i> Cash on Delivery
                        </label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment-credit-card" value="credit_card" required>
                        <label class="form-check-label" for="payment-credit-card">
                            <i class="bi bi-credit-card text-primary"></i> Credit Card
                        </label>
                        <div class="text-muted small mt-1 ms-4">(Payment will be processed at delivery)</div>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment-online" value="online" required>
                        <label class="form-check-label" for="payment-online">
                            <i class="bi bi-phone text-info"></i> Online Payment
                        </label>
                        <div class="text-muted small mt-1 ms-4">(Coming soon)</div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Notes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Additional Notes</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="notes" class="form-label">Special Instructions (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special delivery instructions? Let us know here."></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="fw-bold mb-2">{{ $restaurant->name }}</div>
                        <div class="small text-muted">
                            <i class="bi bi-geo-alt"></i> {{ $restaurant->address ? $restaurant->address->district . ', ' . $restaurant->address->city : 'Location not available' }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="fw-bold mb-2">{{ count($cart['items']) }} Items</div>
                        @foreach($cart['items'] as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="fw-bold">{{ $item['quantity'] }}x</span> {{ $item['name'] }}
                                </div>
                                <div>₺{{ number_format($item['subtotal'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₺{{ number_format($cart['total'], 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee:</span>
                        <span>₺{{ number_format($restaurant->delivery_fee, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>₺{{ number_format($cart['total'] * 0.1, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Total:</span>
                        <span class="fs-5">₺{{ number_format($cart['total'] + $restaurant->delivery_fee + ($cart['total'] * 0.1), 2) }}</span>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" {{ $addresses->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle"></i> Place Order
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Back to Cart
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Estimated Delivery -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock text-primary fs-3 me-3"></i>
                        <div>
                            <h6 class="mb-1">Estimated Delivery Time</h6>
                            <p class="mb-0 text-muted">30-45 minutes after order confirmation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection 