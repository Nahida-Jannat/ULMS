<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../index.php");
    exit();
}

$base_fine = 20; // Fine amount per overdue day
$total_fine = 0;
$total_overdue_books = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Management - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"/>
    <link rel="stylesheet" href="../css/custom.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #2ecc71;
            --light-color: #ecf0f1;
            --dark-color: #1a252f;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2076&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .fines-container {
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
            border-left: 6px solid var(--danger-color);
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
            color: var(--danger-color);
            background: rgba(231, 76, 60, 0.1);
            padding: 15px;
            border-radius: 50%;
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
        
        .stat-icon.overdue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        .stat-icon.fine {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.rate {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
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
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-action {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-print {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-export {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
            padding: 1rem;
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
            transform: scale(1.002);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            text-align: center;
            border: none;
        }
        
        .badge-overdue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge-fine {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .no-fines {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        
        .no-fines i {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }
        
        .no-fines h3 {
            color: #95a5a6;
            margin-bottom: 0.5rem;
        }
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .student-name {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .book-title {
            color: #2c3e50;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .fines-container {
                padding: 1rem;
            }
            
            .header-section {
                padding: 1.5rem;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        @media print {
            body {
                background: white !important;
            }
            
            .fines-table-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            
            .actions,
            .stats-cards,
            .footer,
            .navbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include '_navbar.php'; ?>

<div class="fines-container">
    <div class="header-section">
        <h1><i class="fas fa-exclamation-triangle"></i> Fine Management</h1>
        <p class="subtitle">Track and manage overdue book fines for students</p>
        
        <?php
        // Calculate total fines and overdue books
        $query = "
            SELECT u.user_id AS student_id,
                   u.name AS student_name, 
                   b.title AS book_title, 
                   r.due_date, 
                   DATEDIFF(CURDATE(), r.due_date) AS overdue_days,
                   (DATEDIFF(CURDATE(), r.due_date) * $base_fine) AS fine
            FROM users u
            JOIN reservations r ON u.user_id = r.user_id
            JOIN books b ON r.book_id = b.book_id
            WHERE r.returned = 'No' AND r.due_date < CURDATE()
            ORDER BY r.due_date ASC";

        $result = mysqli_query($conn, $query);
        $total_fine = 0;
        $total_overdue_books = mysqli_num_rows($result);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $total_fine += $row['fine'];
            }
            // Reset result pointer
            mysqli_data_seek($result, 0);
        }
        ?>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon overdue">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-overdue"><?php echo $total_overdue_books; ?></h3>
                    <p>Overdue Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon fine">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-fine">৳<?php echo $total_fine; ?></h3>
                    <p>Total Fine Amount</p>
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
            <h2><i class="fas fa-list"></i> Overdue Book Details</h2>
            <div class="actions">
                <button class="btn-action btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Report
                </button>
                <button class="btn-action btn-export" onclick="exportToCSV()">
                    <i class="fas fa-file-export"></i> Export CSV
                </button>
            </div>
        </div>
        
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Book Title</th>
                            <th>Due Date</th>
                            <th>Overdue Days</th>
                            <th>Fine (BDT)</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $counter = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $overdue_days = max(0, $row['overdue_days']); // Ensure non-negative
                $fine = $overdue_days * $base_fine;
                
                echo "<tr>";
                echo "<td>" . $counter . "</td>";
                echo "<td><strong>" . htmlspecialchars($row['student_id']) . "</strong></td>";
                echo "<td><span class='student-name'>" . htmlspecialchars($row['student_name']) . "</span></td>";
                echo "<td><span class='book-title'>" . htmlspecialchars($row['book_title']) . "</span></td>";
                echo "<td>" . htmlspecialchars(date('M d, Y', strtotime($row['due_date']))) . "</td>";
                echo "<td><span class='badge-overdue'>" . $overdue_days . " days</span></td>";
                echo "<td><span class='badge-fine'>৳" . $fine . "</span></td>";
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
                <h3>No Overdue Books Found</h3>
                <p>All books have been returned on time. Great job!</p>
            </div>';
        }
        ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Fine Management Module</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Export to CSV functionality
    function exportToCSV() {
        const table = document.querySelector('.table');
        const rows = table.querySelectorAll('tr');
        let csv = [];
        
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length; j++) {
                // Remove HTML tags and get clean text
                let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
                // Escape quotes
                text = text.replace(/"/g, '""');
                // Wrap in quotes if contains comma
                if (text.indexOf(',') > -1 || text.indexOf('"') > -1) {
                    text = '"' + text + '"';
                }
                row.push(text);
            }
            csv.push(row.join(","));
        }
        
        const csvString = csv.join("\n");
        const blob = new Blob([csvString], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', 'overdue_fines_report_' + new Date().toISOString().slice(0,10) + '.csv');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
    
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
            
            const dateElement = document.getElementById('current-date');
            if (!dateElement) {
                const subtitle = document.querySelector('.subtitle');
                const dateSpan = document.createElement('span');
                dateSpan.id = 'current-date';
                dateSpan.style.display = 'block';
                dateSpan.style.marginTop = '5px';
                dateSpan.style.color = '#3498db';
                dateSpan.style.fontWeight = '500';
                dateSpan.textContent = 'As of ' + dateString;
                subtitle.appendChild(dateSpan);
            }
        }
        
        updateCurrentDate();
    });
</script>
</body>
</html>