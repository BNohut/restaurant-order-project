<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Restaurant Order System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-box {
            max-width: 600px;
            width: 100%;
            padding: 50px 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-action {
            width: 200px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-box">
            <h1 class="mb-4">Restaurant Order System</h1>
            <p class="lead mb-5">Welcome to our Restaurant Order System. Order delicious food from the comfort of your home or manage your restaurant operations.</p>
            
            <div class="d-flex justify-content-center flex-wrap">
                @if (auth()->check())
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg btn-action">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-action mb-3 mb-md-0">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg btn-action">Register</a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
