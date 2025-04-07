<!DOCTYPE html>
<html>
<head>
    <title>Login - LoveConnect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 350px;
            text-align: center;
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        
        .primary-btn {
            background-color: #5c6bc0;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .primary-btn:hover {
            background-color: #3f51b5;
        }
        
        a {
            color: #5c6bc0;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        p {
            margin: 15px 0;
        }
        
        .discord-login {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .discord-btn {
            display: inline-block;
            background-color: #7289da;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
        
        .discord-btn:hover {
            background-color: #5f73bc;
            text-decoration: none;
        }
        
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
        
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        
        /* Password field container styles */
        .password-container {
            position: relative;
            margin-bottom: 15px;
        }
        
        .password-container input {
            margin-bottom: 0;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 18px;
            user-select: none;
        }
        
        .password-toggle:hover {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <!-- Success message (only shown if redirected from registration) -->
        <p class="success-message" style="display: none;">Registration successful! Please log in.</p>
        
        <!-- Error message (shown if login fails) -->
        <!-- <p class="error-message">Invalid email or password.</p> -->
        
        <form method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            
            <!-- Password field with toggle icon -->
            <div class="password-container">
                <input type="password" id="password-field" name="password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword()">👁️</span>
            </div>
            
            <button type="submit" class="primary-btn">Login</button>
        </form>
        
        <p><a href="forgot-password.php">Forgot Password?</a></p>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        
        <!-- Discord Login Button -->
        <div class="discord-login">
            <a href="login-discord.php" class="discord-btn">Login with Discord</a>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password-field');
            const passwordToggle = document.querySelector('.password-toggle');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.textContent = '🔒';  // Change to locked icon when password is visible
            } else {
                passwordField.type = 'password';
                passwordToggle.textContent = '👁️';  // Change back to eye icon when password is hidden
            }
        }
    </script>
</body>
</html>
