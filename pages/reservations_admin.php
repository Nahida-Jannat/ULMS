<?php
session_start();

// Include the database connection
include '../includes/db_connection.php';

$alertMessage = ''; // Variable to hold alert message
$alertType = '';    // Variable to hold alert type (success or danger)

// Check if the user is logged in and is an admin or staff
if (!isset($_SESSION['user_id'])  || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../index.php");
    exit();
}

// Handle Update Reservation Status
if (isset($_POST['update_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $status = $_POST['status'];

    // Fetch the book ID associated with the reservation
    $reservation_query = "SELECT book_id FROM reservations WHERE reservation_id = '$reservation_id'";
    $reservation_result = mysqli_query($conn, $reservation_query);

    if ($reservation_row = mysqli_fetch_assoc($reservation_result)) {
        $book_id = $reservation_row['book_id'];

        // Update the reservation status
        $update_reservation_query = "UPDATE reservations SET returned='$status' WHERE reservation_id='$reservation_id'";
        $update_reservation_result = mysqli_query($conn, $update_reservation_query);

        if ($update_reservation_result) {
            // Update the `books` table based on the returned status
            if ($status === 'Yes') {
                // If returned, make the book available
                $update_book_query = "UPDATE books SET reserved='No', reserved_by=NULL WHERE book_id='$book_id'";
            } else {
                // If not returned, keep it reserved by the current user
                $update_book_query = "UPDATE books SET reserved='Yes', reserved_by=(SELECT user_id FROM reservations WHERE reservation_id='$reservation_id') WHERE book_id='$book_id'";
            }

            if (mysqli_query($conn, $update_book_query)) {
                $alertMessage = "Reservation and book status updated successfully!";
                $alertType = "success";
            } else {
                $alertMessage = "Error updating book status: " . mysqli_error($conn);
                $alertType = "danger";
            }
        } else {
            $alertMessage = "Error updating reservation: " . mysqli_error($conn);
            $alertType = "danger";
        }
    } else {
        $alertMessage = "Error fetching reservation details.";
        $alertType = "danger";
    }
}

// Handle Delete Reservation
if (isset($_POST['delete_reservation']) && $_SESSION['role'] === 'admin') {
    $reservation_id = $_POST['reservation_id'];

    // Fetch the book ID associated with the reservation
    $reservation_query = "SELECT book_id FROM reservations WHERE reservation_id = '$reservation_id'";
    $reservation_result = mysqli_query($conn, $reservation_query);

    if ($reservation_row = mysqli_fetch_assoc($reservation_result)) {
        $book_id = $reservation_row['book_id'];

        // Delete the reservation
        $delete_reservation_query = "DELETE FROM reservations WHERE reservation_id = '$reservation_id'";
        $delete_reservation_result = mysqli_query($conn, $delete_reservation_query);

        if ($delete_reservation_result) {
            // Update the `books` table to make the book available
            $update_book_query = "UPDATE books SET reserved='No', reserved_by=NULL WHERE book_id='$book_id'";
            if (mysqli_query($conn, $update_book_query)) {
                $alertMessage = "Reservation deleted successfully!";
                $alertType = "success";
            } else {
                $alertMessage = "Error updating book status: " . mysqli_error($conn);
                $alertType = "danger";
            }
        } else {
            $alertMessage = "Error deleting reservation: " . mysqli_error($conn);
            $alertType = "danger";
        }
    } else {
        $alertMessage = "Error fetching reservation details.";
        $alertType = "danger";
    }
}

// Fetch statistics
$total_reservations_query = "SELECT COUNT(*) as total FROM reservations";
$active_reservations_query = "SELECT COUNT(*) as active FROM reservations WHERE returned = 'No'";
$overdue_reservations_query = "SELECT COUNT(*) as overdue FROM reservations WHERE returned = 'No' AND due_date < CURDATE()";
$returned_reservations_query = "SELECT COUNT(*) as returned FROM reservations WHERE returned = 'Yes'";

$total_reservations = mysqli_fetch_assoc(mysqli_query($conn, $total_reservations_query))['total'] ?? 0;
$active_reservations = mysqli_fetch_assoc(mysqli_query($conn, $active_reservations_query))['active'] ?? 0;
$overdue_reservations = mysqli_fetch_assoc(mysqli_query($conn, $overdue_reservations_query))['overdue'] ?? 0;
$returned_reservations = mysqli_fetch_assoc(mysqli_query($conn, $returned_reservations_query))['returned'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Management - Library System</title>
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
            --info-color: #1abc9c;
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
        
        .reservations-container {
            max-width: 1600px;
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
            cursor: pointer;
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
        
        .stat-icon.total {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }
        
        .stat-icon.active {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.overdue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }
        
        .stat-icon.returned {
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
        
        .reservations-table-container {
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
        
        .filter-section {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .filter-select {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: white;
            font-size: 0.9rem;
            color: #333;
            cursor: pointer;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #eaeaea;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .table {
            margin-bottom: 0;
            width: 100%;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table thead th {
            border: none;
            padding: 1.2rem 1rem;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
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
        
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .status-not-returned {
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
        }
        
        .status-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: white;
            font-size: 0.9rem;
            color: #333;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .status-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .btn-action {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            cursor: pointer;
        }
        
        .btn-update {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-delete {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .alert-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        .no-reservations {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
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
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .book-title {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .overdue-row {
            background-color: rgba(231, 76, 60, 0.05) !important;
            border-left: 4px solid #e74c3c !important;
        }
        
        @media (max-width: 1200px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.8rem 0.5rem;
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
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .filter-section {
                width: 100%;
                justify-content: space-between;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn-action {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-cards {
                grid-template-columns: 1fr;
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

<!-- Alert Container -->
<div class="alert-container">
    <?php if ($alertMessage): ?>
        <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas <?php echo $alertType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> me-2"></i>
                <div><?php echo $alertMessage; ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<div class="reservations-container">
    <div class="header-section">
        <h1><i class="fas fa-calendar-alt"></i> Reservation Management</h1>
        <p class="subtitle">Manage all book reservations, update return status, and track overdue items</p>
        
        <div class="stats-cards">
            <div class="stat-card" onclick="filterReservations('all')">
                <div class="stat-icon total">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-reservations"><?php echo $total_reservations; ?></h3>
                    <p>Total Reservations</p>
                </div>
            </div>
            
            <div class="stat-card" onclick="filterReservations('active')">
                <div class="stat-icon active">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 id="active-reservations"><?php echo $active_reservations; ?></h3>
                    <p>Active Reservations</p>
                </div>
            </div>
            
            <div class="stat-card" onclick="filterReservations('overdue')">
                <div class="stat-icon overdue">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="overdue-reservations"><?php echo $overdue_reservations; ?></h3>
                    <p>Overdue Reservations</p>
                </div>
            </div>
            
            <div class="stat-card" onclick="filterReservations('returned')">
                <div class="stat-icon returned">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="returned-reservations"><?php echo $returned_reservations; ?></h3>
                    <p>Returned Reservations</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="reservations-table-container">
        <div class="table-header">
            <h2><i class="fas fa-list"></i> All Reservations</h2>
            <div class="filter-section">
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="not-returned">Not Returned</option>
                    <option value="returned">Returned</option>
                    <option value="overdue">Overdue</option>
                </select>
                <select id="sortFilter" class="filter-select">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="due-date">Due Date</option>
                </select>
            </div>
        </div>
        
        <?php
        // Fetch all reservations
        $query = "SELECT r.reservation_id, r.book_id, b.title AS book_title, r.user_id, 
                         r.reservation_date, r.due_date, r.returned 
                  FROM reservations r
                  JOIN books b ON r.book_id = b.book_id
                  ORDER BY r.reservation_id DESC";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover" id="reservationsTable">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Book ID</th>
                            <th>Book Title</th>
                            <th>User ID</th>
                            <th>Reservation Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                $reservation_id = $row['reservation_id'];
                $isOverdue = strtotime($row['due_date']) < time() && $row['returned'] === 'No';
                $status = $row['returned'];
                
                // Determine status display
                $statusDisplay = '';
                if ($isOverdue) {
                    $statusDisplay = '<span class="status-badge status-overdue">Overdue</span>';
                } elseif ($status === 'Yes') {
                    $statusDisplay = '<span class="status-badge status-returned">Returned</span>';
                } else {
                    $statusDisplay = '<span class="status-badge status-not-returned">Not Returned</span>';
                }
                
                $rowClass = $isOverdue ? 'overdue-row' : '';
                
                echo '<tr class="' . $rowClass . '" 
                         data-status="' . ($isOverdue ? 'overdue' : $status) . '" 
                         data-due-date="' . $row['due_date'] . '"
                         data-reservation-date="' . $row['reservation_date'] . '">
                    <form method="POST" action="">
                        <td><strong>' . $reservation_id . '</strong></td>
                        <td>' . $row['book_id'] . '</td>
                        <td class="book-title" title="' . htmlspecialchars($row['book_title']) . '">' . htmlspecialchars($row['book_title']) . '</td>
                        <td>' . $row['user_id'] . '</td>
                        <td>' . date('M d, Y', strtotime($row['reservation_date'])) . '</td>
                        <td>' . date('M d, Y', strtotime($row['due_date'])) . '</td>
                        <td>
                            ' . $statusDisplay . '
                            <input type="hidden" name="status" value="' . $status . '">
                        </td>
                        <td>
                            <input type="hidden" name="reservation_id" value="' . $reservation_id . '">
                            <div class="action-buttons">
                                <div class="status-select-container" style="display: none;">
                                    <select name="status" class="status-select">
                                        <option value="No" ' . ($status === 'No' ? 'selected' : '') . '>Not Returned</option>
                                        <option value="Yes" ' . ($status === 'Yes' ? 'selected' : '') . '>Returned</option>
                                    </select>
                                </div>
                                <button type="button" class="btn-action btn-update edit-btn" onclick="enableEditMode(this)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <div class="update-buttons" style="display: none;">
                                    <button type="submit" class="btn-action btn-update" name="update_reservation">
                                        <i class="fas fa-check"></i> Save
                                    </button>
                                    <button type="button" class="btn-action btn-secondary" onclick="cancelEditMode(this)">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>';
                
                if ($_SESSION['role'] === 'admin') {
                    echo '<button type="submit" class="btn-action btn-delete" name="delete_reservation" 
                                   onclick="return confirmDelete()">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>';
                }
                
                echo '</div>
                        </td>
                    </form>
                </tr>';
            }
            
            echo '</tbody>
                </table>
            </div>';
        } else {
            echo '
            <div class="no-reservations">
                <i class="fas fa-calendar-times"></i>
                <h3>No Reservations Found</h3>
                <p>There are no book reservations in the system yet.</p>
            </div>';
        }
        ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Reservation Management Module</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
        
        // Add animation to table rows
        const tableRows = document.querySelectorAll('#reservationsTable tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 100 * index);
        });
        
        // Initialize event listeners for filters
        document.getElementById('statusFilter').addEventListener('change', filterTable);
        document.getElementById('sortFilter').addEventListener('change', sortTable);
    });
    
    // Enable edit mode for a row
    function enableEditMode(button) {
        const row = button.closest('tr');
        const editBtn = row.querySelector('.edit-btn');
        const updateButtons = row.querySelector('.update-buttons');
        const statusBadge = row.querySelector('.status-badge');
        const statusSelectContainer = row.querySelector('.status-select-container');
        const hiddenStatusInput = row.querySelector('input[name="status"]');
        
        // Hide edit button, show update buttons and status select
        editBtn.style.display = 'none';
        updateButtons.style.display = 'flex';
        statusBadge.style.display = 'none';
        statusSelectContainer.style.display = 'block';
        
        // Update hidden input value
        const statusSelect = row.querySelector('select[name="status"]');
        hiddenStatusInput.value = statusSelect.value;
        
        // Update hidden input when select changes
        statusSelect.addEventListener('change', function() {
            hiddenStatusInput.value = this.value;
        });
    }
    
    // Cancel edit mode
    function cancelEditMode(button) {
        const row = button.closest('tr');
        const editBtn = row.querySelector('.edit-btn');
        const updateButtons = row.querySelector('.update-buttons');
        const statusBadge = row.querySelector('.status-badge');
        const statusSelectContainer = row.querySelector('.status-select-container');
        
        // Show edit button, hide update buttons and status select
        editBtn.style.display = 'flex';
        updateButtons.style.display = 'none';
        statusBadge.style.display = 'inline-block';
        statusSelectContainer.style.display = 'none';
    }
    
    // Confirm delete action
    function confirmDelete() {
        return confirm('Are you sure you want to delete this reservation? This action cannot be undone.');
    }
    
    // Filter table by status
    function filterTable() {
        const filter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#reservationsTable tbody tr');
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const isOverdue = row.classList.contains('overdue-row');
            
            switch(filter) {
                case 'all':
                    row.style.display = '';
                    break;
                case 'not-returned':
                    row.style.display = status === 'No' && !isOverdue ? '' : 'none';
                    break;
                case 'returned':
                    row.style.display = status === 'Yes' ? '' : 'none';
                    break;
                case 'overdue':
                    row.style.display = isOverdue ? '' : 'none';
                    break;
            }
        });
    }
    
    // Filter reservations by clicking on stat cards
    function filterReservations(type) {
        const filterSelect = document.getElementById('statusFilter');
        
        switch(type) {
            case 'all':
                filterSelect.value = 'all';
                break;
            case 'active':
                filterSelect.value = 'not-returned';
                break;
            case 'overdue':
                filterSelect.value = 'overdue';
                break;
            case 'returned':
                filterSelect.value = 'returned';
                break;
        }
        
        filterTable();
        
        // Highlight the clicked stat card
        document.querySelectorAll('.stat-card').forEach(card => {
            card.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.08)';
        });
        event.currentTarget.style.boxShadow = '0 5px 15px rgba(52, 152, 219, 0.3)';
    }
    
    // Sort table
    function sortTable() {
        const sortBy = document.getElementById('sortFilter').value;
        const tbody = document.querySelector('#reservationsTable tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortBy) {
                case 'newest':
                    aValue = new Date(a.getAttribute('data-reservation-date'));
                    bValue = new Date(b.getAttribute('data-reservation-date'));
                    return bValue - aValue;
                case 'oldest':
                    aValue = new Date(a.getAttribute('data-reservation-date'));
                    bValue = new Date(b.getAttribute('data-reservation-date'));
                    return aValue - bValue;
                case 'due-date':
                    aValue = new Date(a.getAttribute('data-due-date'));
                    bValue = new Date(b.getAttribute('data-due-date'));
                    return aValue - bValue;
                default:
                    return 0;
            }
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }
    
    // Add secondary button style
    const style = document.createElement('style');
    style.textContent = `
        .btn-secondary {
            background-color: #95a5a6 !important;
            color: white !important;
        }
        .btn-secondary:hover {
            background-color: #7f8c8d !important;
        }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>