@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
            </div>
            <div class="card-body">
                <h5>Welcome, {{ Auth::user()->name }}!</h5>
                <p>You are logged in as: 
                    @foreach(Auth::user()->roles as $role)
                        @if($role->name == 'admin')
                            <span class="badge bg-danger"><i class="bi bi-shield-lock me-1"></i>{{ $role->name }}</span>
                        @elseif($role->name == 'manager')
                            <span class="badge bg-info"><i class="bi bi-briefcase me-1"></i>{{ $role->name }}</span>
                        @elseif($role->name == 'courier')
                            <span class="badge bg-success"><i class="bi bi-bicycle me-1"></i>{{ $role->name }}</span>
                        @else
                            <span class="badge bg-primary"><i class="bi bi-person me-1"></i>{{ $role->name }}</span>
                        @endif
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Restaurants Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="section-title">Featured Restaurants</h4>
            <a href="{{ route('restaurants.index') }}" class="btn btn-primary btn-sm">View All Restaurants</a>
        </div>
    </div>
    
    @forelse ($featuredRestaurants as $restaurant)
    <div class="col-md-6 col-lg-3">
        <div class="restaurant-card">
            <div class="restaurant-img {{ Str::slug(explode(' ', $restaurant->name)[0]) }}">
                <span class="featured-badge">Featured</span>
            </div>
            <div class="restaurant-content">
                <h5 class="restaurant-title">{{ $restaurant->name }}</h5>
                <div class="restaurant-info">
                    <div><i class="bi bi-geo-alt"></i> {{ $restaurant->address ? $restaurant->address->city : 'Unknown Location' }}</div>
                    <div><i class="bi bi-clock"></i> 
                        @if(isset($restaurant->business_hours['monday']))
                            {{ $restaurant->business_hours['monday'][0] }} - {{ $restaurant->business_hours['monday'][1] }}
                        @else
                            Hours not available
                        @endif
                    </div>
                </div>
                <a href="{{ route('restaurants.show', $restaurant->uuid) }}" class="btn btn-sm btn-primary w-100">View Menu</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <p>No featured restaurants available at the moment.</p>
    </div>
    @endforelse
</div>

<!-- Featured Products Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="section-title">Featured Menu Items</h4>
    </div>
    
    @forelse ($featuredProducts as $product)
    <div class="col-md-6 col-lg-3">
        <div class="product-card">
            <div class="product-img {{ Str::slug(explode(' ', $product->category)[0]) }}"></div>
            <div class="product-content">
                <h5 class="product-title">{{ $product->name }}</h5>
                <div class="product-price">₺{{ number_format($product->price, 2) }}</div>
                <div class="product-restaurant">
                    <i class="bi bi-shop"></i> 
                    @if($product->company)
                        <a href="{{ route('restaurants.show', $product->company->uuid) }}">
                            {{ $product->company->name }}
                        </a>
                    @else
                        Unknown Restaurant
                    @endif
                </div>
                <p class="small text-muted mt-2">{{ Str::limit($product->description, 60) }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <p>No featured products available at the moment.</p>
    </div>
    @endforelse
</div>

<!-- User Information Section -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="bi bi-person-vcard me-2"></i>User Information</h5>
            </div>
            <div class="card-body user-info">
                <p><strong><i class="bi bi-person me-1"></i> Name:</strong> {{ Auth::user()->name }}</p>
                <p><strong><i class="bi bi-envelope me-1"></i> Email:</strong> {{ Auth::user()->email }}</p>
                @if(Auth::user()->phone)
                    <p><strong><i class="bi bi-telephone me-1"></i> Phone:</strong> {{ Auth::user()->phone }}</p>
                @endif
                
                @if(Auth::user()->identification && Auth::user()->hasAnyRole(['manager', 'courier']))
                    <div class="alert alert-info">
                        <p class="mb-1"><strong><i class="bi bi-card-text me-1"></i> Identification:</strong> {{ Auth::user()->identification }}</p>
                        <p class="mb-0"><strong><i class="bi bi-tag me-1"></i> Type:</strong> 
                            @switch(Auth::user()->identification_type)
                                @case('national_id')
                                    National ID
                                    @break
                                @case('passport')
                                    Passport
                                    @break
                                @case('driving_license')
                                    Driving License
                                    @break
                                @default
                                    {{ Auth::user()->identification_type }}
                            @endswitch
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="bi bi-key me-2"></i>Your Permissions</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach(Auth::user()->getAllPermissions() as $permission)
                        <li class="list-group-item">
                            <i class="bi bi-check-circle me-2 text-success"></i>
                            {{ $permission->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 