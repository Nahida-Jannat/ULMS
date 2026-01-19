<?php
session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

// Fetch the name of the logged-in admin
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard_styles.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"/>
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #1a252f;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .dashboard-wrapper {
            min-height: 100vh;
            padding-bottom: 2rem;
        }
        
        .welcome-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 242, 245, 0.95) 100%);
            border-radius: 15px;
            padding: 2.5rem;
            margin: 2rem auto 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-left: 5px solid var(--secondary-color);
            max-width: 1200px;
            text-align: center;
        }
        
        .welcome-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.8rem;
            font-size: 2.2rem;
        }
        
        .welcome-header p {
            color: #7f8c8d;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .admin-name {
            color: var(--secondary-color);
            font-weight: 700;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .dashboard-title {
            color: var(--light-color);
            text-align: center;
            margin-bottom: 2.5rem;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            font-size: 1.8rem;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 1.5rem;
        }
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-top: 4px solid var(--secondary-color);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }
        
        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }
        
        .dashboard-card i {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
        }
        
        .dashboard-card h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.6rem;
        }
        
        .dashboard-card p {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 0;
            line-height: 1.5;
        }
        
        .card-users {
            border-top-color: #3498db;
        }
        
        .card-users i {
            color: #3498db;
        }
        
        .card-books {
            border-top-color: #2ecc71;
        }
        
        .card-books i {
            color: #2ecc71;
        }
        
        .card-reservations {
            border-top-color: #f39c12;
        }
        
        .card-reservations i {
            color: #f39c12;
        }
        
        .card-fines {
            border-top-color: #e74c3c;
        }
        
        .card-fines i {
            color: #e74c3c;
        }
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 2rem;
            margin-top: 4rem;
            font-size: 0.9rem;
        }
        
        /* Current time display */
        #current-time {
            margin-top: 0.8rem;
            font-size: 1rem;
            color: #3498db;
            font-weight: 500;
            background: rgba(52, 152, 219, 0.1);
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        @media (max-width: 992px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .welcome-header {
                padding: 2rem;
                margin: 1.5rem 1rem 2.5rem;
            }
            
            .welcome-header h1 {
                font-size: 1.8rem;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .dashboard-card {
                padding: 2rem 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .welcome-header {
                padding: 1.5rem;
            }
            
            .welcome-header h1 {
                font-size: 1.6rem;
            }
            
            .dashboard-title {
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }
            
            .dashboard-card i {
                font-size: 3rem;
            }
            
            .dashboard-card h3 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
    <!-- Include Navbar -->
    <?php include '_navbar.php'; ?>
    
    <div class="dashboard-container">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <h1>Welcome back, <span class="admin-name"><?php echo htmlspecialchars($name); ?></span>!</h1>
            <p>Library Management System - Admin Dashboard</p>
            <div id="current-time"></div>
        </div>
        
        <!-- Statistics section has been removed as requested -->
        
        <h2 class="dashboard-title">Management Dashboard</h2>
        
        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <a href="users_admin.php" class="dashboard-card card-users">
                <i class="fas fa-user-cog"></i>
                <h3>Manage Users</h3>
                <p>Add, edit, or remove user accounts and manage permissions for students and staff</p>
            </a>
            
            <a href="books_admin.php" class="dashboard-card card-books">
                <i class="fas fa-book-open"></i>
                <h3>Manage Book Catalog</h3>
                <p>Update library collection, add new books, or modify existing entries and categories</p>
            </a>
            
            <a href="reservations_admin.php" class="dashboard-card card-reservations">
                <i class="fas fa-calendar-alt"></i>
                <h3>View Student Reservations</h3>
                <p>Monitor and manage all current and upcoming book reservations and checkouts</p>
            </a>
            
            <a href="fines_admin.php" class="dashboard-card card-fines">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>View Student Overdues</h3>
                <p>Track overdue books, manage late return penalties, and send notifications</p>
            </a>
        </div>
    </div>
    
    <div class="footer">
        <p>Library Management System &copy; <?php echo date('Y'); ?> | Admin Dashboard</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to cards on load
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 150 * index);
        });
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const timeString = now.toLocaleDateString('en-US', options);
            
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        // Initial time update
        updateTime();
        setInterval(updateTime, 1000); // Update every second
        
        // Add hover sound effect (optional)
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>
</body>
</html>