<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Get reservation statistics
$total_reservations_query = "SELECT COUNT(*) as total FROM reservations WHERE user_id = '$user_id'";
$active_reservations_query = "SELECT COUNT(*) as active FROM reservations WHERE user_id = '$user_id' AND returned = 'No'";
$returned_reservations_query = "SELECT COUNT(*) as returned FROM reservations WHERE user_id = '$user_id' AND returned = 'Yes'";
$overdue_reservations_query = "SELECT COUNT(*) as overdue FROM reservations WHERE user_id = '$user_id' AND returned = 'No' AND due_date < CURDATE()";

$total_reservations = mysqli_fetch_assoc(mysqli_query($conn, $total_reservations_query))['total'] ?? 0;
$active_reservations = mysqli_fetch_assoc(mysqli_query($conn, $active_reservations_query))['active'] ?? 0;
$returned_reservations = mysqli_fetch_assoc(mysqli_query($conn, $returned_reservations_query))['returned'] ?? 0;
$overdue_reservations = mysqli_fetch_assoc(mysqli_query($conn, $overdue_reservations_query))['overdue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"/>
    <style>
        :root {
            --primary-color: #49a0f8;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #2ecc71;
            --info-color: #1abc9c;
            --light-color: #ecf0f1;
            --dark-color: #2469aa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .reservations-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .header-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(250, 250, 252, 0.98) 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-left: 6px solid var(--secondary-color);
        }
        
        .header-section h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-section h1 i {
            color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.1);
            padding: 15px;
            border-radius: 50%;
        }
        
        .student-name {
            color: var(--secondary-color);
            font-weight: 700;
        }
        
        .header-section .subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(19, 85, 129, 0.12);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.2rem;
            font-size: 1.5rem;
        }
        
        .stat-icon.total {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }
        
        .stat-icon.active {
            background-color: rgba(18, 179, 243, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.returned {
            background-color: rgba(144, 201, 250, 0.1);
            color: var(--success-color);
        }
        
        .stat-icon.overdue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }
        
        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            color: var(--primary-color);
        }
        
        .stat-info p {
            color: #7f8c8d;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .reservations-section {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(200, 225, 244, 0.1);
            overflow: hidden;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }
        
        .section-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid #ddd;
            background: white;
            color: #7f8c8d;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }
        
        .reservations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .reservation-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 4px solid var(--secondary-color);
        }
        
        .reservation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .reservation-card.overdue {
            border-top-color: var(--accent-color);
        }
        
        .reservation-card.returned {
            border-top-color: var(--success-color);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .book-info {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 1.5rem;
        }
        
        .book-icon {
            width: 50px;
            height: 50px;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .book-details {
            flex: 1;
        }
        
        .book-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .book-author {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .reservation-details {
            background: rgba(245, 245, 245, 0.8);
            border-radius: 10px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
        }
        
        .detail-label {
            color: #7f8c8d;
            font-weight: 500;
        }
        
        .detail-value {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-top: 10px;
        }
        
        .status-active {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }
        
        .status-returned {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(46, 204, 113, 0.3);
        }
        
        .status-overdue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            border: 1px solid rgba(231, 76, 60, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        .no-reservations {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
            grid-column: 1 / -1;
        }
        
        .no-reservations i {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }
        
        .no-reservations h3 {
            color: #95a5a6;
            margin-bottom: 0.5rem;
        }
        
        .no-reservations p {
            color: #7f8c8d;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.5;
        }
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .days-remaining {
            color: var(--success-color);
            font-weight: 600;
        }
        
        .days-overdue {
            color: var(--accent-color);
            font-weight: 600;
        }
        
        .card-id {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.1);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        @media (max-width: 992px) {
            .reservations-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .reservations-container {
                padding: 1rem;
            }
            
            .header-section {
                padding: 1.5rem;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .filter-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .reservations-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .header-section h1 {
                font-size: 1.6rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                margin-right: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include '_navbar.php'; ?>

<div class="reservations-container">
    <div class="header-section">
        <h1><i class="fas fa-calendar-alt"></i> My Reservations</h1>
        <p class="subtitle">View and manage your book reservations for student <span class="student-name"><?php echo htmlspecialchars($name); ?></span></p>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-reservations"><?php echo $total_reservations; ?></h3>
                    <p>Total Reservations</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 id="active-reservations"><?php echo $active_reservations; ?></h3>
                    <p>Active Reservations</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon returned">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="returned-reservations"><?php echo $returned_reservations; ?></h3>
                    <p>Returned Reservations</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon overdue">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="overdue-reservations"><?php echo $overdue_reservations; ?></h3>
                    <p>Overdue Reservations</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="reservations-section">
        <div class="section-header">
            <h2><i class="fas fa-book"></i> Reservation Details</h2>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterReservations('all')">All</button>
                <button class="filter-btn" onclick="filterReservations('active')">Active</button>
                <button class="filter-btn" onclick="filterReservations('returned')">Returned</button>
                <button class="filter-btn" onclick="filterReservations('overdue')">Overdue</button>
            </div>
        </div>
        
        <?php
        $query = "SELECT b.book_id, b.title, b.author, r.reservation_date, r.due_date, r.reservation_id, r.returned
                  FROM books b
                  JOIN reservations r ON b.book_id = r.book_id
                  WHERE r.user_id = '$user_id'
                  ORDER BY r.reservation_id DESC";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="reservations-grid" id="reservationsGrid">';
            
            while ($row = mysqli_fetch_assoc($result)) {
                $book_id = $row['book_id'];
                $title = htmlspecialchars($row['title']);
                $author = htmlspecialchars($row['author']);
                $reservation_date = date('M d, Y', strtotime($row['reservation_date']));
                $due_date = date('M d, Y', strtotime($row['due_date']));
                $reservation_id = $row['reservation_id'];
                $returned = $row['returned'];
                
                // Determine status
                $is_overdue = (strtotime($row['due_date']) < time()) && $returned === 'No';
                $card_class = '';
                $status_badge = '';
                $days_text = '';
                
                if ($returned === 'Yes') {
                    $card_class = 'returned';
                    $status_badge = '<span class="status-badge status-returned">Returned</span>';
                } elseif ($is_overdue) {
                    $card_class = 'overdue';
                    $overdue_days = floor((time() - strtotime($row['due_date'])) / (60 * 60 * 24));
                    $days_text = '<div class="detail-item"><span class="detail-label">Days Overdue:</span><span class="detail-value days-overdue">' . $overdue_days . ' days</span></div>';
                    $status_badge = '<span class="status-badge status-overdue">Overdue</span>';
                } else {
                    $card_class = 'active';
                    $days_remaining = floor((strtotime($row['due_date']) - time()) / (60 * 60 * 24));
                    $days_text = '<div class="detail-item"><span class="detail-label">Days Remaining:</span><span class="detail-value days-remaining">' . $days_remaining . ' days</span></div>';
                    $status_badge = '<span class="status-badge status-active">Active</span>';
                }
                
                echo '
                <div class="reservation-card ' . $card_class . '" data-status="' . ($returned === 'Yes' ? 'returned' : ($is_overdue ? 'overdue' : 'active')) . '">
                    <div class="card-header">
                        <div class="card-id">#' . $reservation_id . '</div>
                        <h3 title="' . $title . '">' . (strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title) . '</h3>
                    </div>
                    <div class="card-body">
                        <div class="book-info">
                            <div class="book-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="book-details">
                                <div class="book-title" title="' . $title . '">' . (strlen($title) > 40 ? substr($title, 0, 40) . '...' : $title) . '</div>
                                <div class="book-author">by ' . $author . '</div>
                            </div>
                        </div>
                        
                        <div class="reservation-details">
                            <div class="detail-item">
                                <span class="detail-label">Book ID:</span>
                                <span class="detail-value">' . $book_id . '</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Reserved On:</span>
                                <span class="detail-value">' . $reservation_date . '</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Due Date:</span>
                                <span class="detail-value">' . $due_date . '</span>
                            </div>
                            ' . $days_text . '
                        </div>
                        
                        ' . $status_badge . '
                    </div>
                </div>';
            }
            
            echo '</div>';
        } else {
            echo '
            <div class="no-reservations">
                <i class="fas fa-calendar-times"></i>
                <h3>No Reservations Found</h3>
                <p>You haven\'t reserved any books yet. Visit the books section to explore our collection and make your first reservation!</p>
                <a href="books_student.php" class="btn btn-primary mt-3" style="background: var(--secondary-color); border: none; padding: 10px 25px; border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-book-open me-2"></i> Browse Books
                </a>
            </div>';
        }
        ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Student Reservations Portal</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add animation to cards on load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.reservation-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
        
        // Update current date in header
        function updateCurrentDate() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric'
            };
            const dateString = now.toLocaleDateString('en-US', options);
            
            const subtitle = document.querySelector('.subtitle');
            const dateSpan = document.createElement('span');
            dateSpan.style.display = 'block';
            dateSpan.style.marginTop = '10px';
            dateSpan.style.color = '#3498db';
            dateSpan.style.fontWeight = '500';
            dateSpan.style.fontSize = '0.9rem';
            dateSpan.textContent = 'Last updated: ' + dateString;
            subtitle.appendChild(dateSpan);
        }
        
        updateCurrentDate();
    });
    
    // Filter reservations
    function filterReservations(type) {
        const cards = document.querySelectorAll('.reservation-card');
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        // Update active button
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.toLowerCase() === type) {
                btn.classList.add('active');
            }
        });
        
        // Filter cards
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            
            switch(type) {
                case 'all':
                    card.style.display = 'block';
                    break;
                case 'active':
                    card.style.display = status === 'active' ? 'block' : 'none';
                    break;
                case 'returned':
                    card.style.display = status === 'returned' ? 'block' : 'none';
                    break;
                case 'overdue':
                    card.style.display = status === 'overdue' ? 'block' : 'none';
                    break;
            }
        });
    }
</script>
</body>
</html>