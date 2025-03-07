<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Restomate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --sidebar-transition: all 0.3s ease;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-bg: #343a40;
            --content-bg: #f8f9fa;
        }
        
        body {
            background: var(--content-bg);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d0d7de' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            padding: 0;
            color: #333;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: var(--primary-gradient);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            height: 60px;
            padding: 0 20px;
            z-index: 1030;
        }
        
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Main Layout */
        .wrapper {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            transition: var(--sidebar-transition);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1020;
            overflow-y: auto;
            overflow-x: hidden;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar .logo-container {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar .logo-text {
            transition: var(--sidebar-transition);
            opacity: 1;
            white-space: nowrap;
            overflow: hidden;
        }
        
        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
        }
        
        .sidebar-toggle {
            color: #fff;
            background: transparent;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 5px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu .menu-header {
            padding: 12px 15px 5px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }
        
        .sidebar.collapsed .menu-header {
            text-align: center;
            padding: 12px 5px 5px;
        }
        
        .sidebar.collapsed .menu-header span {
            display: none;
        }
        
        .sidebar-menu .menu-item {
            position: relative;
        }
        
        .sidebar-menu .menu-link {
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu .menu-link:hover, 
        .sidebar-menu .menu-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left-color: #764ba2;
        }
        
        .sidebar-menu .menu-icon {
            margin-right: 10px;
            font-size: 1.2rem;
            min-width: 25px;
            text-align: center;
        }
        
        .sidebar-menu .menu-text {
            white-space: nowrap;
            overflow: hidden;
            transition: var(--sidebar-transition);
        }
        
        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
        }
        
        .sidebar-menu .submenu-indicator {
            margin-left: auto;
            transition: transform 0.3s ease;
        }
        
        .sidebar-menu .has-submenu.expanded .submenu-indicator {
            transform: rotate(90deg);
        }
        
        .sidebar-menu .submenu {
            padding-left: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            list-style: none;
            background: rgba(0, 0, 0, 0.15);
        }
        
        .sidebar-menu .has-submenu.expanded .submenu {
            max-height: 1000px;
        }
        
        .sidebar-menu .submenu .menu-link {
            padding-left: 50px;
        }
        
        .sidebar.collapsed .submenu {
            position: absolute;
            left: var(--sidebar-collapsed-width);
            top: 0;
            width: 200px;
            max-height: none;
            visibility: hidden;
            opacity: 0;
            transition: var(--sidebar-transition);
            background: var(--sidebar-bg);
            box-shadow: 5px 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0 5px 5px 0;
            z-index: 1;
        }
        
        .sidebar.collapsed .has-submenu:hover .submenu {
            visibility: visible;
            opacity: 1;
        }
        
        .sidebar.collapsed .submenu .menu-link {
            padding-left: 15px;
        }
        
        /* Main Content Area */
        .content {
            flex: 1;
            transition: var(--sidebar-transition);
            margin-left: var(--sidebar-width);
            padding: 20px;
            width: calc(100% - var(--sidebar-width));
            overflow-x: hidden;
        }
        
        .content.expanded {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .card-header {
            background: var(--primary-gradient);
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
        
        /* Alert Styles */
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
        
        /* Dropdown Menu */
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
        
        /* Section Title */
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
            background: var(--primary-gradient);
        }
        
        /* Restaurant & Product Cards */
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
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
                position: fixed;
                left: calc(-1 * var(--sidebar-width));
                transition: var(--sidebar-transition);
            }
            
            .sidebar.mobile-shown {
                left: 0;
                width: var(--sidebar-width);
            }
            
            .sidebar.mobile-shown .menu-text,
            .sidebar.mobile-shown .logo-text {
                opacity: 1;
                width: auto;
            }
            
            .content {
                margin-left: 0;
                width: 100%;
            }
            
            .content.expanded {
                margin-left: 0;
                width: 100%;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1010;
            }
            
            .overlay.active {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark top-navbar">
        <div class="container-fluid">
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler border-0 me-2 d-lg-none" type="button" id="mobile-sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>
            
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/restomate_title.png') }}" alt="Restomate" height="30" class="d-inline-block align-top">
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 menu-icon"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}" href="{{ route('restaurants.index') }}">
                            <i class="bi bi-shop menu-icon"></i>
                            <span class="menu-text">Restaurants</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- Cart Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            @php
                                $cart = session()->get('cart');
                                $cartCount = $cart['count'] ?? 0;
                            @endphp
                            <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}">
                                {{ $cartCount }}
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="#" class="dropdown-item">
                                    <i class="bi bi-person me-2"></i>My Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Overlay -->
    <div class="overlay" id="sidebar-overlay"></div>

    <!-- Main Content Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <!-- Sidebar Toggle -->
            <div class="logo-container">
                <span class="logo-text fw-bold">Dashboard</span>
                <button class="sidebar-toggle" id="sidebar-toggle">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </div>
            
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Dashboard Link -->
                <li class="menu-item">
                    <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 menu-icon"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <!-- Restaurants Section -->
                <li class="menu-header">
                    <i class="bi bi-shop"></i>
                    <span>Restaurants</span>
                </li>
                <li class="menu-item">
                    <a href="{{ route('restaurants.index') }}" class="menu-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}">
                        <i class="bi bi-shop menu-icon"></i>
                        <span class="menu-text">Browse Restaurants</span>
                    </a>
                </li>
                
                <!-- Cart & Orders Section -->
                <li class="menu-header">
                    <i class="bi bi-cart"></i>
                    <span>My Orders</span>
                </li>
                <li class="menu-item">
                    <a href="{{ route('cart.index') }}" class="menu-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                        <i class="bi bi-cart3 menu-icon"></i>
                        <span class="menu-text">Shopping Cart</span>
                        @php
                            $cart = session()->get('cart');
                            $cartCount = $cart['count'] ?? 0;
                        @endphp
                        @if($cartCount > 0)
                        <span class="badge rounded-pill bg-danger ms-auto">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('orders.index') }}" class="menu-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt menu-icon"></i>
                        <span class="menu-text">My Orders</span>
                    </a>
                </li>

                @if(Auth::user()->hasRole('admin'))
                <!-- System Administration Section -->
                <li class="menu-header">
                    <i class="bi bi-gear"></i>
                    <span>System</span>
                </li>
                <li class="menu-item has-submenu">
                    <a href="#" class="menu-link">
                        <i class="bi bi-people menu-icon"></i>
                        <span class="menu-text">User Management</span>
                        <i class="bi bi-chevron-right submenu-indicator"></i>
                    </a>
                    <ul class="submenu">
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">All Users</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Add New User</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item has-submenu">
                    <a href="#" class="menu-link">
                        <i class="bi bi-shield-lock menu-icon"></i>
                        <span class="menu-text">Role Management</span>
                        <i class="bi bi-chevron-right submenu-indicator"></i>
                    </a>
                    <ul class="submenu">
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Roles</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Permissions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="bi bi-gear menu-icon"></i>
                        <span class="menu-text">System Settings</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <!-- Products Section -->
                <li class="menu-header">
                    <i class="bi bi-box"></i>
                    <span>Products</span>
                </li>
                <li class="menu-item has-submenu">
                    <a href="#" class="menu-link">
                        <i class="bi bi-box2 menu-icon"></i>
                        <span class="menu-text">Product Management</span>
                        <i class="bi bi-chevron-right submenu-indicator"></i>
                    </a>
                    <ul class="submenu">
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">All Products</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Add New Product</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Categories</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <!-- Restaurant Management Section -->
                <li class="menu-header">
                    <i class="bi bi-building"></i>
                    <span>Management</span>
                </li>
                <li class="menu-item has-submenu">
                    <a href="#" class="menu-link">
                        <i class="bi bi-building menu-icon"></i>
                        <span class="menu-text">Restaurant Management</span>
                        <i class="bi bi-chevron-right submenu-indicator"></i>
                    </a>
                    <ul class="submenu">
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">My Restaurants</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Add Restaurant</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <!-- Order Management Section -->
                <li class="menu-header">
                    <i class="bi bi-list-check"></i>
                    <span>Order Management</span>
                </li>
                <li class="menu-item">
                    <a href="{{ route('orders.manage') }}" class="menu-link {{ request()->routeIs('orders.manage') ? 'active' : '' }}">
                        <i class="bi bi-cart-check menu-icon"></i>
                        <span class="menu-text">Manage Orders</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasRole('courier'))
                <!-- Deliveries Section -->
                <li class="menu-header">
                    <i class="bi bi-truck"></i>
                    <span>Deliveries</span>
                </li>
                <li class="menu-item">
                    <a href="{{ route('orders.courier') }}" class="menu-link {{ request()->routeIs('orders.courier') ? 'active' : '' }}">
                        <i class="bi bi-truck menu-icon"></i>
                        <span class="menu-text">My Deliveries</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasRole('client'))
                <!-- Account Section -->
                <li class="menu-header">
                    <i class="bi bi-person"></i>
                    <span>My Account</span>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="bi bi-geo-alt menu-icon"></i>
                        <span class="menu-text">Address Book</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="bi bi-credit-card menu-icon"></i>
                        <span class="menu-text">Payment Methods</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <!-- Reports Section -->
                <li class="menu-header">
                    <i class="bi bi-graph-up"></i>
                    <span>Reports</span>
                </li>
                <li class="menu-item has-submenu">
                    <a href="#" class="menu-link">
                        <i class="bi bi-graph-up menu-icon"></i>
                        <span class="menu-text">Analytics</span>
                        <i class="bi bi-chevron-right submenu-indicator"></i>
                    </a>
                    <ul class="submenu">
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Sales Reports</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">Order Statistics</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <span class="menu-text">User Activity</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="content" id="content">
            @if(session('status'))
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('expanded');
                    
                    // Change icon based on state
                    const icon = sidebarToggle.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('bi-chevron-left');
                        icon.classList.add('bi-chevron-right');
                    } else {
                        icon.classList.remove('bi-chevron-right');
                        icon.classList.add('bi-chevron-left');
                    }
                });
            }
            
            // Mobile sidebar toggle
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.add('mobile-shown');
                    sidebarOverlay.classList.add('active');
                });
            }
            
            // Close sidebar when clicking on overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-shown');
                    sidebarOverlay.classList.remove('active');
                });
            }
            
            // Submenu Toggle
            const submenuToggles = document.querySelectorAll('.has-submenu > .menu-link');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const parent = this.parentElement;
                    
                    // Close other open submenus
                    if (!sidebar.classList.contains('collapsed')) {
                        const openMenus = document.querySelectorAll('.has-submenu.expanded');
                        openMenus.forEach(menu => {
                            if (menu !== parent) {
                                menu.classList.remove('expanded');
                            }
                        });
                    }
                    
                    // Toggle current submenu
                    parent.classList.toggle('expanded');
                });
            });
            
            // Initialize sidebar state from localStorage if available
            const savedState = localStorage.getItem('sidebarState');
            if (savedState === 'collapsed') {
                sidebar.classList.add('collapsed');
                content.classList.add('expanded');
                const icon = sidebarToggle.querySelector('i');
                icon.classList.remove('bi-chevron-left');
                icon.classList.add('bi-chevron-right');
            }
            
            // Save sidebar state to localStorage
            const saveState = () => {
                if (sidebar.classList.contains('collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            };
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', saveState);
            }
        });
    </script>
    @yield('scripts')
</body>
</html> 