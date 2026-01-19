<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$base_fine = 20; // fine amount per day due

// Get student statistics
$reserved_books_query = "SELECT COUNT(*) as reserved FROM reservations WHERE user_id = '$user_id' AND returned = 'No'";
$borrowed_books_query = "SELECT COUNT(*) as borrowed FROM reservations WHERE user_id = '$user_id' AND returned = 'Yes'";
$overdue_books_query = "SELECT COUNT(*) as overdue FROM reservations WHERE user_id = '$user_id' AND returned = 'No' AND due_date < CURDATE()";

$reserved_books = mysqli_fetch_assoc(mysqli_query($conn, $reserved_books_query))['reserved'] ?? 0;
$borrowed_books = mysqli_fetch_assoc(mysqli_query($conn, $borrowed_books_query))['borrowed'] ?? 0;
$overdue_books = mysqli_fetch_assoc(mysqli_query($conn, $overdue_books_query))['overdue'] ?? 0;

// Calculate fines
$query = "SELECT SUM(GREATEST(DATEDIFF(CURDATE(), r.due_date), 0) * $base_fine) AS fine
          FROM reservations r
          WHERE r.user_id = '$user_id' 
          AND r.returned = 'No' 
          AND r.due_date < CURDATE()";
$result = mysqli_query($conn, $query);
$fine = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $fine = $row['fine'] ? $row['fine'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"/>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #2ecc71;
            --light-color: #ecf0f1;
            --dark-color: #1a252f;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.85)), 
                        url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(250, 250, 252, 0.98) 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-left: 6px solid var(--secondary-color);
            text-align: center;
        }
        
        .welcome-section h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.8rem;
            font-size: 2.5rem;
        }
        
        .student-name {
            color: var(--secondary-color);
            font-weight: 800;
        }
        
        .welcome-section p {
            color: #7f8c8d;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        
        .quote-section {
            background: rgba(52, 152, 219, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border-left: 4px solid var(--secondary-color);
        }
        
        #random-quote {
            color: var(--primary-color);
            font-style: italic;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }
        
        .quote-author {
            color: #7f8c8d;
            text-align: right;
            font-weight: 500;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1.8rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-top: 4px solid var(--secondary-color);
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            font-size: 1.8rem;
        }
        
        .stat-icon.reserved {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }
        
        .stat-icon.borrowed {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }
        
        .stat-icon.overdue {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.fine {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }
        
        .stat-card h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .stat-card p {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 280px;
        }
        
        .dashboard-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }
        
        .dashboard-card i {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
        }
        
        .dashboard-card h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        
        .dashboard-card p {
            color: #7f8c8d;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 0;
        }
        
        .card-books {
            border-top: 5px solid var(--secondary-color);
        }
        
        .card-books i {
            color: var(--secondary-color);
        }
        
        .card-reservations {
            border-top: 5px solid var(--success-color);
        }
        
        .card-reservations i {
            color: var(--success-color);
        }
        
        .card-fines {
            border-top: 5px solid var(--accent-color);
        }
        
        .card-fines i {
            color: var(--accent-color);
        }
        
        .fine-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-top: 0.8rem;
            background: rgba(231, 76, 60, 0.1);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
        }
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            margin-top: 3rem;
            font-size: 0.9rem;
        }
        
        .current-time {
            margin-top: 1rem;
            color: #3498db;
            font-weight: 500;
            font-size: 1rem;
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
            .dashboard-container {
                padding: 1rem;
            }
            
            .welcome-section {
                padding: 2rem 1.5rem;
            }
            
            .welcome-section h1 {
                font-size: 2rem;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .dashboard-card {
                padding: 2rem 1.5rem;
                min-height: 250px;
            }
        }
        
        @media (max-width: 576px) {
            .welcome-section h1 {
                font-size: 1.8rem;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .stat-card h3 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include '_navbar.php'; ?>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1>Welcome back, <span class="student-name"><?php echo htmlspecialchars($name); ?></span>!</h1>
        <p>Welcome to your library dashboard! Here you can explore books, manage your reservations, and track your reading journey. Happy reading!</p>
        
        <div class="quote-section">
            <div id="random-quote">Loading inspirational quote...</div>
            <div class="quote-author"></div>
        </div>
        
        <div id="current-time" class="current-time"></div>
    </div>
    
    <!-- Statistics Section -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon reserved">
                <i class="fas fa-bookmark"></i>
            </div>
            <h3><?php echo $reserved_books; ?></h3>
            <p>Current Reservations</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon borrowed">
                <i class="fas fa-book"></i>
            </div>
            <h3><?php echo $borrowed_books; ?></h3>
            <p>Books Borrowed</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon overdue">
                <i class="fas fa-clock"></i>
            </div>
            <h3><?php echo $overdue_books; ?></h3>
            <p>Overdue Books</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon fine">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3>৳<?php echo $fine; ?></h3>
            <p>Total Fine Amount</p>
        </div>
    </div>
    
    <!-- Dashboard Cards -->
    <h2 style="color: white; text-align: center; margin-bottom: 2rem; font-weight: 600; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
        <i class="fas fa-tachometer-alt"></i> Quick Access
    </h2>
    
    <div class="dashboard-cards">
        <a href="books_student.php" class="dashboard-card card-books">
            <i class="fas fa-book-open"></i>
            <h2>Browse Books</h2>
            <p>Explore our extensive collection of books, find your next favorite read, and discover new authors and genres.</p>
        </a>
        
        <a href="reservations_student.php" class="dashboard-card card-reservations">
            <i class="fas fa-calendar-alt"></i>
            <h2>My Reservations</h2>
            <p>View and manage your current book reservations, check due dates, and track your reading history.</p>
        </a>
        
        <a href="fines_student.php" class="dashboard-card card-fines">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>Fines & Payments</h2>
            <p>Check your fine status, view overdue books, and manage payments for any late returns.</p>
            <div class="fine-amount">
                Current Fine: ৳<?php echo $fine; ?>
            </div>
        </a>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Student Dashboard</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fetch inspirational quote
    const proxyUrl = 'https://api.allorigins.win/get?url=';
    const apiUrl = 'https://type.fit/api/quotes';

    async function fetchQuote() {
        try {
            const response = await fetch(`${proxyUrl}${encodeURIComponent(apiUrl)}`);
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

            const data = await response.json();
            const quotes = JSON.parse(data.contents);
            const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];

            document.getElementById('random-quote').innerText = `"${randomQuote.text}"`;
            document.querySelector('.quote-author').innerText = `- ${randomQuote.author || 'Unknown'}`;
        } catch (error) {
            console.error('Error fetching quote:', error);
            document.getElementById('random-quote').innerText = 'The only thing that you absolutely have to know, is the location of the library. - Albert Einstein';
            document.querySelector('.quote-author').innerText = '';
        }
    }

    // Update current time
    function updateCurrentTime() {
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
        document.getElementById('current-time').textContent = timeString;
    }

    // Add animations to cards on load
    document.addEventListener('DOMContentLoaded', function() {
        // Initial calls
        fetchQuote();
        updateCurrentTime();
        
        // Update time every second
        setInterval(updateCurrentTime, 1000);
        
        // Animate cards
        const statCards = document.querySelectorAll('.stat-card');
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        
        // Animate stat cards
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
        
        // Animate dashboard cards
        dashboardCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 300 + (150 * index));
        });
        
        // Add hover sound effect (optional - commented out)
        /*
        dashboardCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                // Optional: Play subtle hover sound
            });
        });
        */
    });
</script>
</body>
</html>