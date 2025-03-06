<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Restaurant Order System</title>
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
        .login-container {
            display: flex;
            align-items: center;
            max-width: 800px;
            width: 100%;
        }
        .login-form {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }
        .login-form::before {
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
        .logo-section {
            margin-left: 40px;
            text-align: center;
        }
        .big-logo {
            max-width: 220px;
            height: auto;
            animation: float 6s ease-in-out infinite;
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
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #a18cd1;
            box-shadow: 0 0 0 0.25rem rgba(161, 140, 209, 0.25);
        }
        .form-check-input:checked {
            background-color: #764ba2;
            border-color: #764ba2;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-form {
                max-width: 90%;
                padding: 20px;
            }
            .logo-section {
                margin-left: 0;
                margin-bottom: 30px;
            }
            .big-logo {
                max-width: 180px;
            }
        }
        a {
            color: #764ba2;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        a:hover {
            color: #667eea;
            text-decoration: underline;
        }
        .title-image {
            max-width: 200px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-form">
                <img src="{{ asset('images/restomate_title.png') }}" alt="Restomate" class="title-image">
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    
                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="{{ route('register') }}" class="fw-bold">Register</a></p>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>Register as: 
                            <a href="{{ route('register') }}">Client</a> | 
                            <a href="{{ route('register.manager') }}">Manager</a> | 
                            <a href="{{ route('register.courier') }}">Courier</a>
                        </p>
                    </div>
                </form>
            </div>
            
            <div class="logo-section">
                <img src="{{ asset('images/restomate.png') }}" alt="Restomate Logo" class="big-logo">
            </div>
        </div>
    </div>
</body>
</html> 