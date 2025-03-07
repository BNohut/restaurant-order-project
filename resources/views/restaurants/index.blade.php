@extends('layouts.app')

@section('title', 'All Restaurants')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="section-title">Restaurants</h2>
        <p class="text-muted">Find your favorite restaurant and order delicious food</p>
    </div>
</div>

<div class="row g-4">
    @forelse ($restaurants as $restaurant)
    <div class="col-md-6 col-lg-4">
        <div class="restaurant-card">
            <div class="restaurant-img {{ Str::slug(explode(' ', $restaurant->name)[0]) }}">
                @if($restaurant->is_featured)
                <span class="featured-badge">Featured</span>
                @endif
            </div>
            <div class="restaurant-content">
                <h5 class="restaurant-title">{{ $restaurant->name }}</h5>
                <div class="restaurant-info">
                    <div><i class="bi bi-geo-alt"></i> {{ $restaurant->address ? $restaurant->address->city : 'Unknown Location' }}, {{ $restaurant->address ? $restaurant->address->district : '' }}</div>
                    <div><i class="bi bi-clock"></i> 
                        @if(isset($restaurant->business_hours['monday']))
                            {{ $restaurant->business_hours['monday'][0] }} - {{ $restaurant->business_hours['monday'][1] }}
                        @else
                            Hours not available
                        @endif
                    </div>
                    <div><i class="bi bi-truck"></i> Min. Order: ₺{{ number_format($restaurant->minimum_order, 2) }}</div>
                </div>
                <p class="small text-muted mb-3">{{ Str::limit($restaurant->description, 100) }}</p>
                <a href="{{ route('restaurants.show', $restaurant->uuid) }}" class="btn btn-primary w-100">View Menu</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <p>No restaurants available at the moment.</p>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $restaurants->links() }}
</div>
@endsection 