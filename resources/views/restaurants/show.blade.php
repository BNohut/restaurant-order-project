@extends('layouts.app')

@section('title', $restaurant->name)

@section('content')
<div class="container">
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
                                    
                                    <button class="btn btn-sm btn-outline-primary w-100 mt-3">
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
</div>

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
.restaurant-title {
    font-weight: 700;
    margin-bottom: 15px;
}
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
.section-title {
    position: relative;
    display: inline-block;
    margin-bottom: 30px;
    font-weight: 700;
}
.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

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
});
</script>
@endsection 