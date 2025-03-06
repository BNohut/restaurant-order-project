<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Restomate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d0d7de' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-box">
            <img src="{{ asset('images/restomate_title.png') }}" alt="Restomate" class="title-image">
            
            <p class="lead mb-5">Welcome to our Restomate - Restaurant Order System. Order delicious food from the comfort of your home or manage your restaurant operations.</p>
            
            @if (auth()->check())
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg btn-action">Go to Dashboard</a>
            @else
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-action">Login</a>
                </div>

                <div class="registration-section">
                    <h5 class="mb-3">Register as:</h5>
                    <div class="role-buttons">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-action">
                            <i class="bi bi-person"></i> Client
                            <p class="small mb-0">Order food from restaurants</p>
                        </a>
                        
                        <a href="{{ route('register.manager') }}" class="btn btn-outline-info btn-action">
                            <i class="bi bi-shop"></i> Restaurant Manager
                            <p class="small mb-0">Manage your restaurant</p>
                        </a>
                        
                        <a href="{{ route('register.courier') }}" class="btn btn-outline-success btn-action">
                            <i class="bi bi-bicycle"></i> Delivery Courier
                            <p class="small mb-0">Deliver orders</p>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
</body>
</html>
