<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Restomate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d0d7de' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            padding-bottom: 40px;
        }
        .navbar {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
            height: 100%;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border: none;
        }
        .card-header h4, .card-header h5 {
            margin-bottom: 0;
        }
        .card-body {
            padding: 25px;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
            padding: 12px 20px;
            transition: all 0.2s ease;
        }
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .alert-info {
            background-color: #cff4fc;
            color: #055160;
            border-left: 4px solid #0dcaf0;
        }
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            padding: 8px 16px;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .user-info p {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .user-info p:last-child {
            border-bottom: none;
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
        .restaurant-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
        }
        .restaurant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        .restaurant-img {
            height: 160px;
            background-color: #e9ecef;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .restaurant-img::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba(0,0,0,0.7) 100%);
        }
        .restaurant-img.burger { background-image: url('https://images.unsplash.com/photo-1586190848861-99aa4a171e90?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .restaurant-img.pizza { background-image: url('https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .restaurant-img.sushi { background-image: url('https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .restaurant-img.taco { background-image: url('https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .restaurant-img.salad { background-image: url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .restaurant-content {
            padding: 20px;
        }
        .restaurant-title {
            font-weight: 700;
            margin-bottom: 5px;
        }
        .restaurant-info {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
        .product-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        .product-img {
            height: 140px;
            background-color: #e9ecef;
            background-size: cover;
            background-position: center;
        }
        .product-img.burger { background-image: url('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.pizza { background-image: url('https://images.unsplash.com/photo-1604382355076-af4b0eb60143?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.sushi { background-image: url('https://images.unsplash.com/photo-1611143669185-af224c5e3252?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.taco { background-image: url('https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.salad { background-image: url('https://images.unsplash.com/photo-1540420773420-3366772f4999?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-content {
            padding: 15px;
        }
        .product-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        .product-price {
            font-weight: 700;
            color: #764ba2;
            margin-bottom: 5px;
        }
        .product-restaurant {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .featured-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/restomate_title.png') }}" alt="Restomate" height="30" class="d-inline-block align-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('restaurants.index') }}">
                            <i class="bi bi-shop me-1"></i>Restaurants
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('status'))
            <div class="alert alert-success mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
            </div>
        @endif

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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 