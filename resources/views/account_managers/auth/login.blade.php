<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Account Manager Login | AboveMarts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --primary: #18c5c5;
            --primary-dark: #0ea5a5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Open Sans', system-ui, sans-serif;
            min-height: 100vh;
            background: url('{{ asset('assets/img/login-bg/login-bg-17.jpg') }}') center/cover no-repeat fixed;
            position: relative;
            color: #fff;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.85));
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 40px 30px;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--primary);
        }

        .subtitle {
            font-size: 14px;
            color: #ddd;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .icon-container {
            margin-bottom: 25px;
        }

        .icon {
            font-size: 42px;
            color: var(--primary);
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(24, 197, 197, 0.25);
            background: rgba(255,255,255,0.15);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            color: #ddd;
        }

        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }

        .error-box {
            background: rgba(220, 53, 69, 0.25);
            border: 1px solid rgba(220, 53, 69, 0.4);
            color: #ffdddd;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }

        /* Mobile Responsiveness */
        @media (max-width: 480px) {
            .login-box {
                padding: 35px 25px;
                border-radius: 14px;
            }

            .logo {
                font-size: 25px;
            }

            .icon {
                font-size: 38px;
            }

            .form-control {
                font-size: 16px; /* Prevents iOS zoom on focus */
                padding: 15px 16px;
            }

            .btn {
                padding: 16px;
                font-size: 16.5px;
            }
        }

        @media (max-width: 360px) {
            .login-box {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-box">

        <!-- Logo & Title -->
        <div class="logo"><span>Above</span>Marts</div>
        <div class="subtitle">Account Manager Portal</div>

        <!-- Icon -->
        <div class="icon-container">
            <i class="fa fa-lock icon"></i>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="error-box">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form id="loginForm" action="{{ route('account-manager.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <input type="email" 
                       name="email" 
                       class="form-control" 
                       placeholder="Email Address" 
                       required>
            </div>

            <div class="form-group">
                <input type="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Password" 
                       required>
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" id="rememberMe">
                <label for="rememberMe">Remember me</label>
            </div>

            <button type="submit" id="loginBtn" class="btn">
                <span id="btnText">Sign In</span>
            </button>
        </form>

    </div>
</div>

<script>
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('btnText');

    form.addEventListener('submit', function() {
        btn.disabled = true;
        btnText.innerText = 'Signing in...';
    });
</script>

@include('sweetalert::alert')

</body>
</html>