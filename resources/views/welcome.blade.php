<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Restomate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d0d7de' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            color: #333;
        }
        .hero-section {
            padding: 80px 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.85) 100%), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            margin-bottom: 50px;
        }
        .welcome-box {
            max-width: 700px;
            width: 100%;
            padding: 50px 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }
        .welcome-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, 
                #ff9a9e 0%, 
                #fad0c4 20%, 
                #fad0c4 40%, 
                #a18cd1 60%, 
                #fbc2eb 80%, 
                #ff9a9e 100%);
        }
        .btn-action {
            margin: 0 10px 10px 0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-outline-primary {
            border-color: #764ba2;
            color: #764ba2;
        }
        .btn-outline-info {
            border-color: #0dcaf0;
            color: #0dcaf0;
        }
        .btn-outline-success {
            border-color: #20c997;
            color: #20c997;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .role-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .registration-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .logo {
            max-width: 100px;
            height: auto;
            animation: float 6s ease-in-out infinite;
            margin-bottom: 10px;
        }
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        .title-image {
            max-width: 250px;
            height: auto;
            margin: 15px 0 25px;
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
            left: 50%;
            transform: translateX(-50%);
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
        footer {
            background: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        .cta-section {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            padding: 60px 0;
            color: white;
            text-align: center;
            margin: 50px 0;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <img src="{{ asset('images/restomate_title.png') }}" alt="Restomate" class="title-image">
            <h1 class="mb-4">Delicious Food Delivered to Your Door</h1>
            <p class="lead mb-5">Order from the best restaurants in your area with our easy-to-use platform.</p>
            
            @if (auth()->check())
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg btn-action">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg btn-action">Login to Order</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg btn-action">Sign Up</a>
            @endif
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Featured Restaurants Section -->
        <section class="mb-5">
            <h2 class="text-center section-title">Featured Restaurants</h2>
            <div class="row g-4">
                @forelse ($featuredCompanies as $company)
                <div class="col-md-6 col-lg-3">
                    <div class="restaurant-card">
                        <div class="restaurant-img {{ Str::slug(explode(' ', $company->name)[0]) }}" style="background-image: url('');">
                            <span class="featured-badge">Featured</span>
                        </div>
                        <div class="restaurant-content">
                            <h5 class="restaurant-title">{{ $company->name }}</h5>
                            <div class="restaurant-info">
                                <div><i class="bi bi-geo-alt"></i> {{ $company->address ? $company->address->city : 'Unknown Location' }}</div>
                                <div><i class="bi bi-clock"></i> 
                                    @if(isset($company->business_hours['monday']))
                                        {{ $company->business_hours['monday'][0] }} - {{ $company->business_hours['monday'][1] }}
                                    @else
                                        Hours not available
                                    @endif
                                </div>
                            </div>
                            <p class="small text-muted">{{ Str::limit($company->description, 100) }}</p>
                            <a href="#" class="btn btn-sm btn-primary w-100">View Menu</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p>No featured restaurants available at the moment.</p>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="mb-5">
            <h2 class="text-center section-title">Featured Menu Items</h2>
            <div class="row g-4">
                @forelse ($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-img {{ Str::slug(explode(' ', $product->category)[0]) }}"></div>
                        <div class="product-content">
                            <h5 class="product-title">{{ $product->name }}</h5>
                            <div class="product-price">₺{{ number_format($product->price, 2) }}</div>
                            <div class="product-restaurant">
                                <i class="bi bi-shop"></i> {{ $product->company ? $product->company->name : 'Unknown Restaurant' }}
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
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <h2 class="mb-4">Ready to Order?</h2>
                <p class="lead mb-4">Create an account now and enjoy delicious meals delivered straight to your door!</p>
                <div class="d-flex justify-content-center flex-wrap">
                    @if (!auth()->check())
                    <div class="role-buttons">
                        <a href="{{ route('register') }}" class="btn btn-light btn-action">
                            <i class="bi bi-person"></i> Register as a Client
                            <p class="small mb-0">Order food from restaurants</p>
                        </a>
                        
                        <a href="{{ route('register.manager') }}" class="btn btn-outline-light btn-action">
                            <i class="bi bi-shop"></i> Register as a Restaurant Manager
                            <p class="small mb-0">Manage your restaurant</p>
                        </a>
                        
                        <a href="{{ route('register.courier') }}" class="btn btn-outline-light btn-action">
                            <i class="bi bi-bicycle"></i> Register as a Delivery Courier
                            <p class="small mb-0">Deliver orders</p>
                        </a>
                    </div>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg btn-action">Go to Dashboard</a>
                    @endif
                </div>
            </div>
        </section>

        <!-- All Restaurants Section (Condensed) -->
        <section class="mb-5">
            <h2 class="text-center section-title">All Restaurants</h2>
            <div class="row g-4">
                @forelse ($activeCompanies as $company)
                <div class="col-md-6 col-lg-3">
                    <div class="restaurant-card">
                        <div class="restaurant-img {{ Str::slug(explode(' ', $company->name)[0]) }}"></div>
                        <div class="restaurant-content">
                            <h5 class="restaurant-title">{{ $company->name }}</h5>
                            <div class="restaurant-info">
                                <div><i class="bi bi-geo-alt"></i> {{ $company->address ? $company->address->city : 'Unknown Location' }}</div>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">View Menu</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p>No restaurants available at the moment.</p>
                </div>
                @endforelse
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>About Restomate</h5>
                    <p>Our platform connects customers with local restaurants for convenient food delivery and pickup services.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">About Us</a></li>
                        <li><a href="#" class="text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="mb-0">&copy; {{ date('Y') }} Restomate. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
