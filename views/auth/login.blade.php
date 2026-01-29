<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management</title>
    <link rel="stylesheet" type="text/css" href="<?= url('/assets/auth.css') ?>?v=<?= time() ?>">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p class="auth-subtitle">Please sign in to your account.<br>For now, please use kritika as username and admin123 as password.</p>
                
            </div>

            @if($error && !isset($_POST['username']))
                <div class="error-message">
                    <i class="error-icon">⚠</i>
                    <span>{{{ $error }}}</span>
                </div>
            @endif

            <form method="POST" action="<?= url('/login') ?>" class="auth-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="login-btn">
                    <span>Sign In</span>
                </button>
            </form>

            <div class="auth-footer">
                <p>Inventory Management System</p>
            </div>
        </div>
    </div>

    <div id="main-content">
        @yield('content')
    </div>

    <script src="<?= url('/assets/auth.js') ?>"></script>
</body>

</html>