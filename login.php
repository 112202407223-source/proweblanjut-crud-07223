<?php
// login.php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card Login */
        .login-card {
            background: white;
            width: 380px;
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 26px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            color: #718096;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fafbfc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Button */
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 5px;
        }

        .login-btn:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Alert */
        .alert {
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            background: #fed7d7;
            color: #c53030;
            text-align: center;
        }

        /* Demo Info */
        .demo-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .demo-info p {
            font-size: 12px;
            color: #718096;
            margin-bottom: 10px;
        }

        .credentials {
            background: #f7fafc;
            padding: 10px;
            border-radius: 10px;
            font-size: 12px;
            font-family: monospace;
            color: #2d3748;
        }

        .credentials span {
            color: #667eea;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="header">
            <h2>Login</h2>
            <p>Masuk ke Sistem Inventaris</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert">
                Username atau password salah
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
            <div class="alert" style="background: #c6f6d5; color: #276749;">
                Anda telah logout
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autofocus placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="demo-info">
            <p>Akun Demo</p>
            <div class="credentials">
                <span>admin</span> / admin123 &nbsp;&nbsp;|&nbsp;&nbsp;
                <span>user1</span> / password123
            </div>
        </div>

        <div class="footer">
            Sistem Manajemen Inventaris
        </div>
    </div>
</body>
</html>