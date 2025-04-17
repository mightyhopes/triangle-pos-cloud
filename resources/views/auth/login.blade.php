<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Login | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e1e2d 0%, #181824 100%);
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .container {
            padding: 20px;
        }
        
        .card {
            background-color: #27293d;
            border: 1px solid #2d2d43;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
            max-width: 100%;
        }
        
        .text-muted {
            color: #b8b9c3 !important;
        }
        
        .form-control {
            background-color: #1a1a27;
            border-color: #2d2d43;
            color: #e0e0e0;
            border-radius: 5px;
            height: 45px;
        }
        
        .form-control:focus {
            background-color: #1a1a27;
            border-color: #3699ff;
            color: #e0e0e0;
            box-shadow: 0 0 0 0.2rem rgba(54, 153, 255, 0.25);
        }
        
        .input-group-text {
            background-color: #3699ff;
            color: white;
            border: none;
            border-radius: 5px 0 0 5px;
            padding: 0 15px;
        }
        
        .btn-primary {
            background: linear-gradient(118deg, #3699ff, #5a61f4);
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.4);
            height: 45px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(54, 153, 255, 0.5);
        }
        
        .btn-link {
            color: #3699ff;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-link:hover {
            color: #5a61f4;
            text-decoration: none;
        }
        
        .brand-text {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: bold;
            color: white;
            text-shadow: 0 0 20px rgba(54, 153, 255, 0.7);
            transition: all 0.5s ease;
            margin-bottom: 2rem;
        }
        
        .brand-text:hover {
            text-shadow: 0 0 30px rgba(54, 153, 255, 0.9);
        }
        
        .login-footer {
            color: #b8b9c3;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .card {
                padding: 1rem !important;
            }
            
            .row {
                margin-left: 0;
                margin-right: 0;
            }
            
            .col-6 {
                padding: 0 5px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .text-muted {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body class="c-app">
<div class="container">
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <div class="brand-text">Gudangku</div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5 col-sm-12">
            @if(Session::has('account_deactivated'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('account_deactivated') }}
                </div>
            @endif
            <div class="card p-4 border-0">
                <div class="card-body">
                    <form id="login" method="post" action="{{ url('/login') }}">
                        @csrf
                        <h1 class="mb-3">Login</h1>
                        <p class="text-muted mb-4">Sign In to your account</p>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="bi bi-person"></i>
                                    </span>
                            </div>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}"
                                   placeholder="Email">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="bi bi-lock"></i>
                                    </span>
                            </div>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password" name="password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button id="submit" class="btn btn-primary"
                                        type="submit">
                                    Login
                                    <div id="spinner" class="spinner-border text-light" role="status"
                                         style="height: 20px;width: 20px;margin-left: 5px;display: none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center mt-5 login-footer">
            </p>
        </div>
    </div>
</div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>
<script>
    let login = document.getElementById('login');
    let submit = document.getElementById('submit');
    let email = document.getElementById('email');
    let password = document.getElementById('password');
    let spinner = document.getElementById('spinner')

    login.addEventListener('submit', (e) => {
        submit.disabled = true;
        email.readonly = true;
        password.readonly = true;

        spinner.style.display = 'block';

        login.submit();
    });

    setTimeout(() => {
        submit.disabled = false;
        email.readonly = false;
        password.readonly = false;

        spinner.style.display = 'none';
    }, 3000);
</script>

</body>
</html>
