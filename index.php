<?php
session_start();
// Check if the user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Redirect the user based on their role
    if ($_SESSION['role'] == 'admin') {
        header("Location: pages/dashboard_admin.php");
        exit;
    } elseif ($_SESSION['role'] == 'staff') {
        header("Location: pages/dashboard_staff.php");
        exit;
    } elseif ($_SESSION['role'] == 'student') {
        header("Location: pages/dashboard_student.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon"/>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, 
                rgba(44, 62, 80, 0.9) 0%, 
                rgba(52, 152, 219, 0.8) 100%),
                url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        .bg-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite linear;
        }

        .bg-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .bg-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: 5s;
            animation-duration: 15s;
        }

        .bg-circle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 10%;
            animation-delay: 10s;
            animation-duration: 25s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
            }
        }

        .login-container {
            width: 100%;
            max-width: 1200px;
            z-index: 2;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }

        .welcome-section h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #ecf0f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 400px;
            line-height: 1.6;
        }

        .welcome-features {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 400px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }

        .feature-item i {
            color: var(--secondary-color);
            background: rgba(255, 255, 255, 0.1);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form-section {
            padding: 50px 40px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            padding: 10px;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
            border: 4px solid white;
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        h2 {
            color: var(--primary-color);
            font-weight: 800;
            margin-bottom: 30px;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
            border-radius: 2px;
        }

        .form-label {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            background: white;
        }

        .input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--secondary-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-top: 10px;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
            background: linear-gradient(135deg, #2980b9, var(--secondary-color));
        }

        .btn-minimal {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(52, 152, 219, 0.1);
            border: 2px solid transparent;
        }

        .btn-minimal:hover {
            background: rgba(52, 152, 219, 0.2);
            border-color: var(--secondary-color);
            transform: translateX(5px);
        }

        .go-back {
            margin-top: 30px;
            text-align: center;
        }

        .role-badges {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .role-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge-admin {
            background: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .badge-staff {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }

        .badge-student {
            background: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(46, 204, 113, 0.3);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-color);
            display: none;
        }

        .error-message.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .welcome-section {
                padding: 40px 30px;
            }

            .login-form-section {
                padding: 40px 30px;
            }

            .welcome-section h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .login-card {
                flex-direction: column;
            }

            .welcome-section {
                padding: 40px 20px;
                display: none; /* Hide welcome section on mobile */
            }

            .login-form-section {
                padding: 40px 20px;
            }

            .welcome-features {
                grid-template-columns: 1fr;
                max-width: 300px;
            }

            h2 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 0 15px;
            }

            .btn-primary {
                padding: 12px 20px;
            }

            .role-badges {
                flex-direction: column;
                align-items: center;
            }

            .logo-container img {
                width: 100px;
                height: 100px;
            }
        }

        /* Loading animation */
        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--secondary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background 0.3s ease;
            border-radius: 2px;
        }
    </style>
</head>

<body>
    <!-- Animated background elements -->
    <div class="bg-elements">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    <div class="login-container">
        <div class="login-card d-flex flex-lg-row flex-column">
            <!-- Welcome Section -->
            <div class="welcome-section col-lg-6">
                <h1>Welcome to ULMS</h1>
                <p>Your portal for accessing all academic and library services efficiently.</p>
                
                <div class="welcome-features">
                    <div class="feature-item">
                        <i class="fas fa-book"></i>
                        <span>Access Digital Library</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-laptop"></i>
                        <span>Manage Resources</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-bell"></i>
                        <span>Get Notifications</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Track Progress</span>
                    </div>
                </div>

                <div class="role-badges mt-4">
                    <div class="role-badge badge-admin">
                        <i class="fas fa-user-shield"></i>
                        Administrator
                    </div>
                    <div class="role-badge badge-staff">
                        <i class="fas fa-user-tie"></i>
                        Library Staff
                    </div>
                    <div class="role-badge badge-student">
                        <i class="fas fa-user-graduate"></i>
                        Student
                    </div>
                </div>
            </div>

            <!-- Login Form Section -->
            <div class="login-form-section col-lg-6">
                <div class="form-container">
                    <div class="logo-container text-center">
                        <img src="images/logo.png" alt="ULMS Logo">
                    </div>
                    
                    <h2 class="text-center">Sign In</h2>
                    
                    <!-- Error Message Container -->
                    <?php if (isset($_GET['error'])): ?>
                    <div class="error-message show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php 
                            $error = $_GET['error'];
                            if ($error == 'invalid') {
                                echo 'Invalid User ID or Password';
                            } elseif ($error == 'empty') {
                                echo 'Please fill in all fields';
                            } elseif ($error == 'inactive') {
                                echo 'Account is inactive';
                            } else {
                                echo 'Login failed. Please try again.';
                            }
                        ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="authenticate.php" id="loginForm">
                        <div class="mb-4">
                            <label for="user_id" class="form-label">
                                <i class="fas fa-user"></i> User ID
                            </label>
                            <input type="text" class="form-control" id="user_id" name="user_id" required
                                   placeholder="Enter your user ID">
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="Enter your password">
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- Password strength indicator -->
                            <div class="password-strength">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                        </div>
                        
                        <div class="loading" id="loading">
                            <div class="spinner"></div>
                            <p class="mt-2 text-muted">Authenticating...</p>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="loginButton">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </form>
                </div>
                
                <div class="go-back">
                    <a href="pages/important_contact.php" class="btn-minimal">
                        <i class="fas fa-phone-alt me-2"></i> Important Contact
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.className = 'fas fa-eye-slash';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.className = 'fas fa-eye';
                }
            });
            
            // Form submission with loading animation
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loading = document.getElementById('loading');
            
            loginForm.addEventListener('submit', function(e) {
                const userId = document.getElementById('user_id').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!userId || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                    return;
                }
                
                // Show loading animation
                loginButton.style.display = 'none';
                loading.style.display = 'block';
                
                // Simulate network delay for better UX
                setTimeout(() => {
                    // Form will submit normally
                }, 1000);
            });
            
            // Password strength indicator
            const strengthBar = document.getElementById('strengthBar');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length > 0) strength += 20;
                if (password.length >= 6) strength += 20;
                if (/[A-Z]/.test(password)) strength += 20;
                if (/[0-9]/.test(password)) strength += 20;
                if (/[^A-Za-z0-9]/.test(password)) strength += 20;
                
                strengthBar.style.width = strength + '%';
                
                if (strength < 40) {
                    strengthBar.style.background = '#e74c3c';
                } else if (strength < 80) {
                    strengthBar.style.background = '#f39c12';
                } else {
                    strengthBar.style.background = '#2ecc71';
                }
            });
            
            // Auto-focus on user ID field
            document.getElementById('user_id').focus();
            
            // Add animation to form elements on load
            const formElements = document.querySelectorAll('.form-control, .btn-primary, .btn-minimal');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 100 * index);
            });
            
            // Error message auto-dismiss
            const errorMessage = document.querySelector('.error-message');
            if (errorMessage && errorMessage.classList.contains('show')) {
                setTimeout(() => {
                    errorMessage.style.transition = 'opacity 0.5s ease';
                    errorMessage.style.opacity = '0';
                    setTimeout(() => {
                        errorMessage.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>