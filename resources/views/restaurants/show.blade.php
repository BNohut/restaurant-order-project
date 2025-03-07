@extends('layouts.app')

@section('title', $restaurant->name)

@section('content')
<!-- Restaurant Header Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card restaurant-detail-card">
            <div class="restaurant-banner {{ Str::slug(explode(' ', $restaurant->name)[0]) }}">
                @if($restaurant->is_featured)
                <span class="featured-badge">Featured</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="restaurant-title">{{ $restaurant->name }}</h2>
                        <p class="restaurant-description">{{ $restaurant->description }}</p>
                        
                        <div class="restaurant-meta mb-3">
                            @if($restaurant->address)
                            <p class="mb-1">
                                <i class="bi bi-geo-alt text-primary"></i> 
                                {{ $restaurant->address->address_line1 }}, 
                                {{ $restaurant->address->district }}, 
                                {{ $restaurant->address->city }}, 
                                {{ $restaurant->address->country }}
                                @if($restaurant->address->postal_code)
                                 - {{ $restaurant->address->postal_code }}
                                @endif
                            </p>
                            @endif
                            
                            @if($restaurant->phone)
                            <p class="mb-1">
                                <i class="bi bi-telephone text-primary"></i> {{ $restaurant->phone }}
                            </p>
                            @endif
                            
                            @if($restaurant->email)
                            <p class="mb-1">
                                <i class="bi bi-envelope text-primary"></i> {{ $restaurant->email }}
                            </p>
                            @endif
                            
                            @if($restaurant->website)
                            <p class="mb-1">
                                <i class="bi bi-globe text-primary"></i> 
                                <a href="https://{{ $restaurant->website }}" target="_blank">{{ $restaurant->website }}</a>
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="restaurant-info-card">
                            <h5><i class="bi bi-clock"></i> Business Hours</h5>
                            <ul class="list-unstyled hours-list">
                                @if(isset($restaurant->business_hours))
                                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                        @if(isset($restaurant->business_hours[$day]))
                                        <li>
                                            <span class="day">{{ ucfirst($day) }}</span>
                                            <span class="hours">{{ $restaurant->business_hours[$day][0] }} - {{ $restaurant->business_hours[$day][1] }}</span>
                                        </li>
                                        @endif
                                    @endforeach
                                @else
                                <li>Hours not available</li>
                                @endif
                            </ul>
                            
                            <hr>
                            
                            <h5><i class="bi bi-truck"></i> Delivery Information</h5>
                            <ul class="list-unstyled delivery-info">
                                <li><i class="bi bi-cash"></i> Minimum Order: <strong>₺{{ number_format($restaurant->minimum_order, 2) }}</strong></li>
                                <li><i class="bi bi-currency-dollar"></i> Delivery Fee: <strong>₺{{ number_format($restaurant->delivery_fee, 2) }}</strong></li>
                                <li><i class="bi bi-geo-alt"></i> Delivery Radius: <strong>{{ $restaurant->delivery_radius }} km</strong></li>
                            </ul>
                            
                            <button class="btn btn-primary btn-lg w-100 mt-3">
                                <i class="bi bi-cart"></i> Start Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Section -->
<div class="row">
    <div class="col-md-12">
        <h3 class="section-title">Menu</h3>
    </div>
    
    @if($productsByCategory->count() > 0)
        <!-- Category Navigation -->
        <div class="col-md-12 mb-4">
            <div class="category-nav">
                <ul class="nav nav-pills">
                    @foreach($productsByCategory as $category => $products)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#category-{{ Str::slug($category) }}">{{ $category }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <!-- Products by Category -->
        <div class="col-md-12">
            @foreach($productsByCategory as $category => $products)
            <div class="menu-category" id="category-{{ Str::slug($category) }}">
                <h4 class="category-title">{{ $category }}</h4>
                
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card menu-item">
                            <div class="product-img {{ Str::slug($category) }}"></div>
                            <div class="product-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="product-title">{{ $product->name }}</h5>
                                    <div class="product-price">₺{{ number_format($product->price, 2) }}</div>
                                </div>
                                
                                <p class="small text-muted mb-3">{{ $product->description }}</p>
                                
                                @if($product->preparation_time)
                                <div class="prep-time small">
                                    <i class="bi bi-clock"></i> Prep Time: {{ $product->preparation_time }} min
                                </div>
                                @endif
                                
                                <button class="btn btn-sm btn-outline-primary w-100 mt-3 add-to-cart-btn" 
                                    data-product-uuid="{{ $product->uuid }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-price="{{ $product->price }}">
                                    <i class="bi bi-plus-circle"></i> Add to Order
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="col-12 text-center py-5">
            <p>No menu items available for this restaurant.</p>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
.restaurant-detail-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
.restaurant-banner {
    height: 250px;
    background-size: cover;
    background-position: center;
    position: relative;
}
.restaurant-banner.burger { background-image: url('https://images.unsplash.com/photo-1586190848861-99aa4a171e90?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80'); }
.restaurant-banner.pizza { background-image: url('https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80'); }
.restaurant-banner.sushi { background-image: url('https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80'); }
.restaurant-banner.taco { background-image: url('https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80'); }
.restaurant-banner.green { background-image: url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80'); }
.restaurant-description {
    color: #666;
    margin-bottom: 20px;
}
.restaurant-meta {
    color: #666;
}
.restaurant-info-card {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}
.hours-list, .delivery-info {
    margin-bottom: 20px;
}
.hours-list li {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}
.day {
    font-weight: 600;
}
.category-nav {
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}
.category-nav .nav-pills {
    display: inline-flex;
}
.category-nav .nav-pills .nav-link {
    margin-right: 10px;
    border-radius: 20px;
    padding: 8px 20px;
    color: #333;
}
.category-nav .nav-pills .nav-link.active {
    background-color: #764ba2;
    color: white;
}
.menu-category {
    margin-bottom: 40px;
}
.category-title {
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.menu-item {
    margin-bottom: 20px;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for category navigation
    document.querySelectorAll('.category-nav .nav-link').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const categoryId = this.getAttribute('href');
            const category = document.querySelector(categoryId);
            
            if (category) {
                // Update active state
                document.querySelectorAll('.category-nav .nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                this.classList.add('active');
                
                // Scroll to category
                window.scrollTo({
                    top: category.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productUuid = this.getAttribute('data-product-uuid');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            
            // Show quantity modal
            showAddToCartModal(productUuid, productName, productPrice);
        });
    });
    
    // Function to show add to cart modal
    function showAddToCartModal(productUuid, productName, productPrice) {
        // Create modal if it doesn't exist
        if (!document.getElementById('addToCartModal')) {
            const modalHtml = `
            <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addToCartModalLabel">Add to Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addToCartForm">
                                <input type="hidden" id="product_uuid" name="product_uuid">
                                
                                <div class="mb-3">
                                    <label class="form-label">Item</label>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold" id="modal-product-name"></span>
                                        <span class="text-primary" id="modal-product-price"></span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary" id="decrease-quantity">-</button>
                                        <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="20">
                                        <button type="button" class="btn btn-outline-secondary" id="increase-quantity">+</button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Special Instructions (optional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="E.g., no onions, extra sauce, etc."></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <span class="fw-bold">Subtotal:</span>
                                    <span class="fs-5 fw-bold text-primary" id="modal-subtotal"></span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmAddToCart">Add to Order</button>
                        </div>
                    </div>
                </div>
            </div>`;
            
            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Setup quantity buttons
            document.getElementById('decrease-quantity').addEventListener('click', function() {
                const quantityInput = document.getElementById('quantity');
                if (quantityInput.value > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                    updateSubtotal();
                }
            });
            
            document.getElementById('increase-quantity').addEventListener('click', function() {
                const quantityInput = document.getElementById('quantity');
                if (quantityInput.value < 20) {
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    updateSubtotal();
                }
            });
            
            // Update subtotal when quantity changes
            document.getElementById('quantity').addEventListener('change', updateSubtotal);
            
            // Handle add to cart confirmation
            document.getElementById('confirmAddToCart').addEventListener('click', function() {
                const form = document.getElementById('addToCartForm');
                const formData = new FormData(form);
                
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_uuid: formData.get('product_uuid'),
                        quantity: parseInt(formData.get('quantity')),
                        notes: formData.get('notes')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addToCartModal'));
                        modal.hide();
                        
                        // Show success toast
                        showToast('Success', 'Item added to your order', 'success');
                        
                        // Update cart count in the header if you have one
                        updateCartBadge(data.cart.count);
                    } else {
                        // Handle error for different restaurant
                        if (data.currentRestaurant) {
                            if (confirm(data.message)) {
                                // User confirmed they want to clear the cart
                                clearCart().then(() => {
                                    // Try adding to cart again after clearing
                                    addToCart(formData);
                                });
                            }
                        } else {
                            // Show error toast
                            showToast('Error', data.message, 'danger');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error', 'Something went wrong', 'danger');
                });
            });
        }
        
        // Set modal values
        document.getElementById('product_uuid').value = productUuid;
        document.getElementById('modal-product-name').textContent = productName;
        document.getElementById('modal-product-price').textContent = '₺' + parseFloat(productPrice).toFixed(2);
        document.getElementById('quantity').value = 1;
        
        // Update subtotal
        updateSubtotal();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
        modal.show();
    }
    
    // Helper function to update subtotal
    function updateSubtotal() {
        const price = parseFloat(document.getElementById('modal-product-price').textContent.replace('₺', ''));
        const quantity = parseInt(document.getElementById('quantity').value);
        const subtotal = price * quantity;
        
        document.getElementById('modal-subtotal').textContent = '₺' + subtotal.toFixed(2);
    }
    
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
    
    // Helper function to add to cart (used after clearing)
    function addToCart(formData) {
        return fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_uuid: formData.get('product_uuid'),
                quantity: parseInt(formData.get('quantity')),
                notes: formData.get('notes')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addToCartModal'));
                modal.hide();
                
                // Show success toast
                showToast('Success', 'Item added to your order', 'success');
                
                // Update cart count in the header
                updateCartBadge(data.cart.count);
            }
        });
    }
    
    // Helper function to show toast
    function showToast(title, message, type) {
        // Create toast container if it doesn't exist
        if (!document.querySelector('.toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        const container = document.querySelector('.toast-container');
        
        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;
        
        container.insertAdjacentHTML('beforeend', toastHtml);
        
        // Show the toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }
    
    // Helper function to update cart badge
    function updateCartBadge(count) {
        const cartBadge = document.getElementById('cart-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
});
</script>
@endsection 