<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$base_fine = 20; // Fine amount per overdue day
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Calculate total fine
$total_fine_query = "
    SELECT SUM(
        CASE 
            WHEN DATEDIFF(CURDATE(), r.due_date) > 0 
            THEN (DATEDIFF(CURDATE(), r.due_date) * $base_fine)
            ELSE 0
        END
    ) AS total_fine
    FROM reservations r
    WHERE r.user_id = '$user_id' AND r.returned = 'No' AND r.due_date < CURDATE()";

$total_fine_result = mysqli_query($conn, $total_fine_query);
$total_fine_data = mysqli_fetch_assoc($total_fine_result);
$total_fine = $total_fine_data['total_fine'] ?? 0;

// Get overdue books count
$overdue_count_query = "
    SELECT COUNT(*) as overdue_count
    FROM reservations r
    WHERE r.user_id = '$user_id' 
    AND r.returned = 'No' 
    AND r.due_date < CURDATE()";

$overdue_count_result = mysqli_query($conn, $overdue_count_query);
$overdue_count_data = mysqli_fetch_assoc($overdue_count_result);
$overdue_count = $overdue_count_data['overdue_count'] ?? 0;

// Get current reservations
$current_reservations_query = "
    SELECT COUNT(*) as current_reservations
    FROM reservations r
    WHERE r.user_id = '$user_id' AND r.returned = 'No'";

$current_reservations_result = mysqli_query($conn, $current_reservations_query);
$current_reservations_data = mysqli_fetch_assoc($current_reservations_result);
$current_reservations = $current_reservations_data['current_reservations'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fines - Library System</title>
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
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .fines-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .header-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(250, 250, 252, 0.98) 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-left: 6px solid var(--accent-color);
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
            color: var(--accent-color);
            background: rgba(231, 76, 60, 0.1);
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
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
        
        .stat-icon.total-fine {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }
        
        .stat-icon.overdue {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.reservations {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }
        
        .stat-icon.rate {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
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
        
        .fines-table-container {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }
        
        .table-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #eaeaea;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
        }
        
        .table thead th {
            border: none;
            padding: 1.2rem 1rem;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #eee;
        }
        
        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .table tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            text-align: center;
            border: none;
        }
        
        .badge-overdue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }
        
        .badge-fine {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid rgba(243, 156, 18, 0.3);
        }
        
        .badge-on-time {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }
        
        .no-fines {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        
        .no-fines i {
            font-size: 4rem;
            color: #2ecc71;
            margin-bottom: 1rem;
        }
        
        .no-fines h3 {
            color: #2ecc71;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .no-fines p {
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
        
        .book-title {
            color: var(--primary-color);
            font-weight: 500;
            font-style: italic;
        }
        
        .payment-info {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.1), rgba(52, 152, 219, 0.1));
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--success-color);
        }
        
        .payment-info h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .payment-info ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #7f8c8d;
        }
        
        .payment-info li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }
        
        @media (max-width: 768px) {
            .fines-container {
                padding: 1rem;
            }
            
            .header-section {
                padding: 1.5rem;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.8rem 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .header-section h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include '_navbar.php'; ?>

<div class="fines-container">
    <div class="header-section">
        <h1><i class="fas fa-exclamation-triangle"></i> My Fines</h1>
        <p class="subtitle">Track your overdue books and fine details for student <span class="student-name"><?php echo htmlspecialchars($name); ?></span></p>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon total-fine">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3>৳<?php echo $total_fine; ?></h3>
                    <p>Total Fine Amount</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon overdue">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $overdue_count; ?></h3>
                    <p>Overdue Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon reservations">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $current_reservations; ?></h3>
                    <p>Current Reservations</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon rate">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="stat-info">
                    <h3>৳<?php echo $base_fine; ?>/day</h3>
                    <p>Fine Rate Per Day</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="fines-table-container">
        <div class="table-header">
            <h2><i class="fas fa-list"></i> Fine Details</h2>
        </div>
        
        <?php
        $query = "
            SELECT b.title AS book_title, 
                   r.due_date, 
                   DATEDIFF(CURDATE(), r.due_date) AS overdue_days,
                   CASE 
                       WHEN DATEDIFF(CURDATE(), r.due_date) > 0 
                       THEN (DATEDIFF(CURDATE(), r.due_date) * $base_fine)
                       ELSE 0
                   END AS fine
            FROM reservations r
            JOIN books b ON r.book_id = b.book_id
            WHERE r.user_id = '$user_id' AND r.returned = 'No'
            ORDER BY fine DESC";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Book Title</th>
                            <th>Due Date</th>
                            <th>Overdue Days</th>
                            <th>Fine (BDT)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $counter = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $overdue_days = max(0, $row['overdue_days']);
                $fine = $overdue_days * $base_fine;
                
                // Determine status badge
                $status_badge = '';
                if ($overdue_days > 0) {
                    $status_badge = '<span class="badge-overdue">Overdue</span>';
                } else {
                    $status_badge = '<span class="badge-on-time">On Time</span>';
                }
                
                echo "<tr>";
                echo "<td>" . $counter . "</td>";
                echo "<td class='book-title'>" . htmlspecialchars($row['book_title']) . "</td>";
                echo "<td>" . date('M d, Y', strtotime($row['due_date'])) . "</td>";
                echo "<td><span class='badge-overdue'>" . $overdue_days . " days</span></td>";
                echo "<td><span class='badge-fine'>৳" . $fine . "</span></td>";
                echo "<td>" . $status_badge . "</td>";
                echo "</tr>";
                $counter++;
            }
            
            echo '</tbody>
                </table>
            </div>';
        } else {
            echo '
            <div class="no-fines">
                <i class="fas fa-check-circle"></i>
                <h3>No Fines Found!</h3>
                <p>Great job! You have no overdue books or pending fines. Keep up the good work of returning books on time!</p>
            </div>';
        }
        ?>
        
        <?php if ($total_fine > 0): ?>
        <div class="payment-info">
            <h4><i class="fas fa-info-circle"></i> Payment Information</h4>
            <ul>
                <li>Total amount due: <strong>৳<?php echo $total_fine; ?></strong></li>
                <li>Fine rate: ৳<?php echo $base_fine; ?> per day for each overdue book</li>
                <li>Please pay your fines at the library front desk during working hours</li>
                <li>You won\'t be able to reserve new books until your fines are cleared</li>
                <li>For payment queries, contact library staff at library@campus.edu</li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Student Fines Portal</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add animation to table rows
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            
            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
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
</script>
</body>
</html>