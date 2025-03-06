<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($role) && $role === 'manager')
        <title>Manager Registration - Restaurant Order System</title>
    @elseif(isset($role) && $role === 'courier')
        <title>Courier Registration - Restaurant Order System</title>
    @else
        <title>Client Registration - Restaurant Order System</title>
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d0d7de' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            padding-top: 40px;
            padding-bottom: 40px;
            min-height: 100vh;
        }
        .register-container {
            display: flex;
            align-items: center;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }
        .register-form {
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }
        .register-form::before {
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
        .role-badge {
            font-size: 0.9rem;
            padding: 0.3rem 0.5rem;
            margin-left: 0.5rem;
            vertical-align: middle;
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
            .register-container {
                flex-direction: column;
            }
            .register-form {
                max-width: 90%;
                padding: 20px;
                margin: 0 auto;
            }
            .logo-section {
                margin-left: 0;
                margin-bottom: 30px;
                order: -1;
            }
            .big-logo {
                max-width: 180px;
            }
        }
        .role-links a {
            color: #764ba2;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .role-links a:hover {
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
        <div class="register-container">
            <div class="register-form">
                <img src="{{ asset('images/restomate_title.png') }}" alt="Restomate" class="title-image">
                
                @if(isset($role) && $role === 'manager')
                    <h2 class="text-center mb-4">Manager Registration <span class="badge bg-info role-badge">Manager</span></h2>
                @elseif(isset($role) && $role === 'courier')
                    <h2 class="text-center mb-4">Courier Registration <span class="badge bg-success role-badge">Courier</span></h2>
                @else
                    <h2 class="text-center mb-4">Client Registration <span class="badge bg-primary role-badge">Client</span></h2>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(isset($role) && $role === 'manager')
                    <form method="POST" action="{{ route('register.manager') }}">
                @elseif(isset($role) && $role === 'courier')
                    <form method="POST" action="{{ route('register.courier') }}">
                @else
                    <form method="POST" action="{{ route('register') }}">
                @endif
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number {{ (isset($role) && ($role === 'manager' || $role === 'courier')) ? 'Required' : '(Optional)' }}</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" {{ (isset($role) && ($role === 'manager' || $role === 'courier')) ? 'required' : '' }}>
                    </div>
                    
                    @if(isset($role) && ($role === 'manager' || $role === 'courier'))
                    <div class="mb-3">
                        <label for="identification" class="form-label">Identification Number (Required)</label>
                        <input type="text" class="form-control" id="identification" name="identification" value="{{ old('identification') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="identification_type" class="form-label">Identification Type (Required)</label>
                        <select class="form-select" id="identification_type" name="identification_type" required>
                            <option value="" selected disabled>Select identification type</option>
                            <option value="national_id" {{ old('identification_type') == 'national_id' ? 'selected' : '' }}>National ID</option>
                            <option value="passport" {{ old('identification_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                            <option value="driving_license" {{ old('identification_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                            <option value="other" {{ old('identification_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="form-text">
                            @if($role === 'manager')
                                Please select the type of identification you're providing for manager verification.
                            @else
                                Please select the type of identification you're providing for courier verification.
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="{{ route('login') }}" class="fw-bold">Login</a></p>
                    </div>
                    
                    <div class="text-center mt-3 role-links">
                        <p>Register as: 
                            @if($role !== 'client')
                                <a href="{{ route('register') }}">Client</a>
                            @else
                                <strong>Client</strong>
                            @endif
                            
                            | 
                            
                            @if($role !== 'manager')
                                <a href="{{ route('register.manager') }}">Manager</a>
                            @else
                                <strong>Manager</strong>
                            @endif
                            
                            | 
                            
                            @if($role !== 'courier')
                                <a href="{{ route('register.courier') }}">Courier</a>
                            @else
                                <strong>Courier</strong>
                            @endif
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