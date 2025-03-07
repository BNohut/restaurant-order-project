@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="section-title">Your Order</h2>
    </div>
</div>

@if(empty($cart['items']))
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-cart3 text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-4">Your cart is empty</h3>
                    <p class="text-muted">Explore our restaurants and add some delicious items to your cart!</p>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-shop"></i> Browse Restaurants
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bag"></i> Items from {{ $restaurant->name }}
                    </h5>
                    <button class="btn btn-sm btn-outline-danger clear-cart-btn">
                        <i class="bi bi-trash"></i> Clear Cart
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart['items'] as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item['name'] }}</div>
                                        @if(!empty($item['notes']))
                                            <div class="small text-muted">Note: {{ $item['notes'] }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button class="btn btn-sm btn-outline-secondary me-2 update-quantity-btn" 
                                                data-action="decrease" 
                                                data-product-uuid="{{ $item['uuid'] }}">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <span class="fw-bold">{{ $item['quantity'] }}</span>
                                            <button class="btn btn-sm btn-outline-secondary ms-2 update-quantity-btn" 
                                                data-action="increase" 
                                                data-product-uuid="{{ $item['uuid'] }}">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-end">₺{{ number_format($item['price'], 2) }}</td>
                                    <td class="text-end fw-bold">₺{{ number_format($item['subtotal'], 2) }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger remove-item-btn" 
                                            data-product-uuid="{{ $item['uuid'] }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            @if($restaurant)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shop"></i> Restaurant Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ $restaurant->name }}</h5>
                            <p class="text-muted">{{ $restaurant->description }}</p>
                            
                            @if($restaurant->address)
                            <p class="mb-2">
                                <i class="bi bi-geo-alt text-primary"></i> 
                                {{ $restaurant->address->address_line1 }}, 
                                {{ $restaurant->address->district }}, 
                                {{ $restaurant->address->city }}
                            </p>
                            @endif
                            
                            @if($restaurant->phone)
                            <p class="mb-2">
                                <i class="bi bi-telephone text-primary"></i> {{ $restaurant->phone }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <p class="mb-2"><i class="bi bi-cash"></i> Minimum Order: <strong>₺{{ number_format($restaurant->minimum_order, 2) }}</strong></p>
                                <p class="mb-2"><i class="bi bi-truck"></i> Delivery Fee: <strong>₺{{ number_format($restaurant->delivery_fee, 2) }}</strong></p>
                                <p class="mb-0"><i class="bi bi-clock"></i> Estimated Delivery: <strong>30-45 min</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₺{{ number_format($cart['total'], 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee:</span>
                        <span>₺{{ number_format($restaurant->delivery_fee ?? 0, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>₺{{ number_format($cart['total'] * 0.1, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Total:</span>
                        <span class="fs-5">₺{{ number_format($cart['total'] + ($restaurant->delivery_fee ?? 0) + ($cart['total'] * 0.1), 2) }}</span>
                    </div>
                    
                    @if($restaurant && $cart['total'] < $restaurant->minimum_order)
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle-fill"></i> 
                            Minimum order amount is ₺{{ number_format($restaurant->minimum_order, 2) }}. 
                            You need to add ₺{{ number_format($restaurant->minimum_order - $cart['total'], 2) }} more to proceed.
                        </div>
                        <a href="{{ route('restaurants.show', $restaurant->uuid) }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Add More Items
                        </a>
                    @else
                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100">
                            <i class="bi bi-credit-card"></i> Proceed to Checkout
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Confirmation Modal for Clear Cart -->
<div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearCartModalLabel">Clear Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to clear your cart? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmClearCart">Clear Cart</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Remove Item -->
<div class="modal fade" id="removeItemModal" tabindex="-1" aria-labelledby="removeItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeItemModalLabel">Remove Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item from your cart?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemoveItem">Remove</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear cart functionality
    const clearCartBtn = document.querySelector('.clear-cart-btn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('clearCartModal'));
            modal.show();
        });
        
        document.getElementById('confirmClearCart').addEventListener('click', function() {
            clearCart().then(() => {
                window.location.reload();
            });
        });
    }
    
    // Remove item functionality
    const removeItemBtns = document.querySelectorAll('.remove-item-btn');
    let itemToRemove = null;
    
    removeItemBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            itemToRemove = this.getAttribute('data-product-uuid');
            const modal = new bootstrap.Modal(document.getElementById('removeItemModal'));
            modal.show();
        });
    });
    
    document.getElementById('confirmRemoveItem').addEventListener('click', function() {
        if (itemToRemove) {
            removeItem(itemToRemove).then(() => {
                window.location.reload();
            });
        }
    });
    
    // Update quantity functionality
    const updateQuantityBtns = document.querySelectorAll('.update-quantity-btn');
    
    updateQuantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productUuid = this.getAttribute('data-product-uuid');
            const action = this.getAttribute('data-action');
            
            // Find the current quantity
            const quantityElement = this.parentElement.querySelector('span');
            let quantity = parseInt(quantityElement.textContent);
            
            if (action === 'increase') {
                quantity += 1;
            } else if (action === 'decrease' && quantity > 1) {
                quantity -= 1;
            }
            
            updateQuantity(productUuid, quantity).then(() => {
                window.location.reload();
            });
        });
    });
    
    // Helper function to clear cart
    function clearCart() {
        return fetch('{{ route('cart.clear') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json());
    }
    
    // Helper function to remove item
    function removeItem(productUuid) {
        return fetch('{{ route('cart.remove') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_uuid: productUuid
            })
        })
        .then(response => response.json());
    }
    
    // Helper function to update quantity
    function updateQuantity(productUuid, quantity) {
        return fetch('{{ route('cart.update') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_uuid: productUuid,
                quantity: quantity
            })
        })
        .then(response => response.json());
    }
});
</script>
@endsection 