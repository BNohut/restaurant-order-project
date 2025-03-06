<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Restomate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d0d7de' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            padding-bottom: 40px;
            color: #333;
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
        .restaurant-img.green { background-image: url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
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
        .product-img.burger, .product-img.burgers { background-image: url('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.pizza, .product-img.pizzas { background-image: url('https://images.unsplash.com/photo-1604382355076-af4b0eb60143?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.sushi, .product-img.nigiri, .product-img.rolls, .product-img.sashimi { background-image: url('https://images.unsplash.com/photo-1611143669185-af224c5e3252?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.taco, .product-img.tacos, .product-img.burritos { background-image: url('https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.salad, .product-img.salads, .product-img.vegetarian, .product-img.bowls { background-image: url('https://images.unsplash.com/photo-1540420773420-3366772f4999?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.sides { background-image: url('https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.desserts { background-image: url('https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.drinks, .product-img.soups { background-image: url('https://images.unsplash.com/photo-1603569283847-aa295f0d016a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
        .product-img.breakfast { background-image: url('https://images.unsplash.com/photo-1533089860892-a7c6f10a081a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'); }
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
    @yield('styles')
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
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}" href="{{ route('restaurants.index') }}">
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

    <main>
        @if(session('status'))
            <div class="container">
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html> 