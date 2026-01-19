<?php
// Start the session
session_start();
include '../includes/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$alertMessage = ''; // Variable for alerts
$alertType = '';    // Variable for alert type

// Handle the book reservation
if (isset($_POST['reserve_book'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $reservation_date = date("Y-m-d");
    $due_date = date("Y-m-d", strtotime("+7 days"));

    $check_query = "SELECT * FROM reservations WHERE book_id = '$book_id' AND returned = 'No'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $alertMessage = "Sorry, this book is already reserved by another student.";
        $alertType = "warning";
    } else {
        $reserve_query = "INSERT INTO reservations (book_id, user_id, reservation_date, due_date) 
                          VALUES ('$book_id', '$user_id', '$reservation_date', '$due_date')";
        $update_query = "UPDATE books SET reserved = 'Yes' WHERE book_id = '$book_id'";

        if (mysqli_query($conn, $reserve_query) && mysqli_query($conn, $update_query)) {
            $alertMessage = "Book reserved successfully! You can collect it from the library.";
            $alertType = "success";
        } else {
            $alertMessage = "Error reserving the book. Please try again.";
            $alertType = "danger";
        }
    }
}

// Default query
$query = "SELECT * FROM books";

// Handle search and filter
$search_term = '';
$genre_filter = '';
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $genre_filter = mysqli_real_escape_string($conn, $_POST['genre_filter']);

    // Add conditions to the query based on user input
    $conditions = [];
    if (!empty($search_term)) {
        $conditions[] = "(title LIKE '%$search_term%' OR author LIKE '%$search_term%' OR genre LIKE '%$search_term%')";
    }
    if (!empty($genre_filter)) {
        $conditions[] = "genre = '$genre_filter'";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }
}
$query .= " ORDER BY title ASC"; //Order By title

// Fetch genres for the dropdown
$genres_query = "SELECT DISTINCT genre FROM books ORDER BY genre";
$genres_result = mysqli_query($conn, $genres_query);

// Get book statistics
$total_books_query = "SELECT COUNT(*) as total FROM books";
$available_books_query = "SELECT COUNT(*) as available FROM books WHERE reserved = 'No'";
$reserved_books_query = "SELECT COUNT(*) as reserved FROM books WHERE reserved = 'Yes'";
$genres_count_query = "SELECT COUNT(DISTINCT genre) as genres FROM books";

$total_books = mysqli_fetch_assoc(mysqli_query($conn, $total_books_query))['total'] ?? 0;
$available_books = mysqli_fetch_assoc(mysqli_query($conn, $available_books_query))['available'] ?? 0;
$reserved_books = mysqli_fetch_assoc(mysqli_query($conn, $reserved_books_query))['reserved'] ?? 0;
$genres_count = mysqli_fetch_assoc(mysqli_query($conn, $genres_count_query))['genres'] ?? 0;

// Get user's current reservations count
$user_reservations_query = "SELECT COUNT(*) as user_reservations FROM reservations WHERE user_id = '$user_id' AND returned = 'No'";
$user_reservations_result = mysqli_query($conn, $user_reservations_query);
$user_reservations_data = mysqli_fetch_assoc($user_reservations_result);
$user_reservations = $user_reservations_data['user_reservations'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books - Library System</title>
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
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .books-container {
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
        
        .stat-icon.available {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }
        
        .stat-icon.reserved {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.your-reservations {
            background-color: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
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
        
        .search-section {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 15px;
            align-items: end;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .btn-search {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3);
        }
        
        .books-section {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
        
        /* Row-wise book table */
        .books-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .books-table thead {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
        }
        
        .books-table th {
            padding: 1.2rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .books-table tbody tr {
            background: white;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .books-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            transform: scale(1.002);
        }
        
        .books-table td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
        }
        
        .book-row-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .book-icon-small {
            width: 40px;
            height: 40px;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .book-info-row {
            flex: 1;
            min-width: 0; /* Prevents overflow */
        }
        
        .book-title-row {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 3px;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }
        
        .book-author-row {
            color: #7f8c8d;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 250px;
        }
        
        .badge-genre-row {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid rgba(52, 152, 219, 0.3);
            display: inline-block;
            white-space: nowrap;
        }
        
        .book-isbn-row {
            color: #7f8c8d;
            font-size: 0.9rem;
            font-family: monospace;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .status-available {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(46, 204, 113, 0.3);
        }
        
        .status-reserved {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }
        
        .btn-reserve-row {
            background: linear-gradient(135deg, var(--success-color), #27ae60);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .btn-reserve-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .btn-reserve-row:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .alert-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        .no-books {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        
        .no-books i {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }
        
        .no-books h3 {
            color: #95a5a6;
            margin-bottom: 0.5rem;
        }
        
        .no-books p {
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
        
        .reservation-limit {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(52, 152, 219, 0.1));
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--warning-color);
            font-size: 0.9rem;
            color: var(--primary-color);
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #eaeaea;
            max-height: 600px;
            overflow-y: auto;
        }
        
        @media (max-width: 1200px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .book-title-row {
                max-width: 200px;
            }
            
            .book-author-row {
                max-width: 180px;
            }
        }
        
        @media (max-width: 768px) {
            .books-container {
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
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .books-table {
                min-width: 800px;
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

<!-- Navbar -->
<?php include '_navbar.php'; ?>

<!-- Alert Container -->
<div class="alert-container">
    <?php if ($alertMessage): ?>
        <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas <?php echo $alertType === 'success' ? 'fa-check-circle' : ($alertType === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle'); ?> me-2"></i>
                <div><?php echo $alertMessage; ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<div class="books-container">
    <div class="header-section">
        <h1><i class="fas fa-book-open"></i> Browse Library Books</h1>
        <p class="subtitle">Explore our collection and reserve books for student <span class="student-name"><?php echo htmlspecialchars($name); ?></span></p>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-books"><?php echo $total_books; ?></h3>
                    <p>Total Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon available">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="available-books"><?php echo $available_books; ?></h3>
                    <p>Available Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon reserved">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div class="stat-info">
                    <h3 id="reserved-books"><?php echo $reserved_books; ?></h3>
                    <p>Reserved Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon your-reservations">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="your-reservations"><?php echo $user_reservations; ?></h3>
                    <p>Your Reservations</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Section -->
    <div class="search-section">
        <form method="POST">
            <div class="search-form">
                <div class="form-group">
                    <label for="search_term" class="form-label">Search Books</label>
                    <input type="text" name="search_term" class="form-control" 
                           placeholder="Search by title, author, or genre" 
                           value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <div class="form-group">
                    <label for="genre_filter" class="form-label">Filter by Genre</label>
                    <select name="genre_filter" class="form-control">
                        <option value="">All Genres</option>
                        <?php 
                        // Reset pointer for genres
                        mysqli_data_seek($genres_result, 0);
                        while ($genre = mysqli_fetch_assoc($genres_result)): ?>
                            <option value="<?php echo htmlspecialchars($genre['genre']); ?>"
                                <?php echo $genre_filter === $genre['genre'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($genre['genre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="search" class="btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Books Section -->
    <div class="books-section">
        <div class="section-header">
            <h2><i class="fas fa-list"></i> Available Books</h2>
            <div>
                <small class="text-muted">
                    <?php 
                    $count_result = mysqli_query($conn, $query);
                    $total_rows = mysqli_num_rows($count_result);
                    echo "Showing " . $total_rows . " books"; 
                    ?>
                </small>
            </div>
        </div>
        
        <?php if ($user_reservations >= 5): ?>
        <div class="reservation-limit">
            <i class="fas fa-exclamation-circle me-2"></i>
            You have reached the maximum limit of 5 active reservations. Please return some books before reserving new ones.
        </div>
        <?php endif; ?>
        
        <?php
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo '
            <div class="table-responsive">
                <table class="books-table">
                    <thead>
                        <tr>
                            <th>Book Details</th>
                            <th>Genre</th>
                            <th>ISBN</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while ($book = mysqli_fetch_assoc($result)) {
                $book_id = $book['book_id'];
                $title = htmlspecialchars($book['title']);
                $author = htmlspecialchars($book['author']);
                $genre = htmlspecialchars($book['genre']);
                $isbn = htmlspecialchars($book['isbn']);
                $reserved = $book['reserved'];
                
                echo '<tr>';
                
                // Book Details Column
                echo '<td>
                        <div class="book-row-item">
                            <div class="book-icon-small">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="book-info-row">
                                <div class="book-title-row" title="' . $title . '">
                                    ' . $title . '
                                </div>
                                <div class="book-author-row" title="by ' . $author . '">
                                    by ' . $author . '
                                </div>
                                <div style="font-size: 0.8rem; color: #7f8c8d; margin-top: 3px;">
                                    ID: ' . $book_id . '
                                </div>
                            </div>
                        </div>
                      </td>';
                
                // Genre Column
                echo '<td><span class="badge-genre-row">' . $genre . '</span></td>';
                
                // ISBN Column
                echo '<td><span class="book-isbn-row">' . $isbn . '</span></td>';
                
                // Status Column
                if ($reserved == 'Yes') {
                    echo '<td><span class="status-badge status-reserved">Reserved</span></td>';
                } else {
                    echo '<td><span class="status-badge status-available">Available</span></td>';
                }
                
                // Action Column
                echo '<td>';
                if ($reserved == 'No' && $user_reservations < 5) {
                    echo '
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="book_id" value="' . $book_id . '">
                            <button type="submit" name="reserve_book" class="btn-reserve-row">
                                <i class="fas fa-calendar-plus"></i> Reserve
                            </button>
                        </form>';
                } elseif ($reserved == 'No' && $user_reservations >= 5) {
                    echo '
                        <button class="btn-reserve-row" disabled>
                            <i class="fas fa-ban"></i> Limit Reached
                        </button>';
                } else {
                    echo '
                        <button class="btn-reserve-row" disabled style="background: #95a5a6;">
                            <i class="fas fa-clock"></i> Already Reserved
                        </button>';
                }
                echo '</td>';
                
                echo '</tr>';
            }
            
            echo '</tbody>
                </table>
            </div>';
        } else {
            echo '
            <div class="no-books">
                <i class="fas fa-book-open"></i>
                <h3>No Books Found</h3>
                <p>' . (!empty($search_term) || !empty($genre_filter) 
                    ? 'No books match your search criteria. Try a different search term or genre.' 
                    : 'There are currently no books in the library catalog.') . '</p>
            </div>';
        }
        ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Student Books Portal</p>
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
        const tableRows = document.querySelectorAll('.books-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            
            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 100 * index);
        });
        
        // Add reservation confirmation
        const reserveButtons = document.querySelectorAll('.btn-reserve-row:not(:disabled)');
        reserveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const row = this.closest('tr');
                const bookTitle = row.querySelector('.book-title-row').textContent;
                if (!confirm('Are you sure you want to reserve "' + bookTitle + '" for 7 days?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Add tooltips for truncated text
        const bookTitles = document.querySelectorAll('.book-title-row');
        const bookAuthors = document.querySelectorAll('.book-author-row');
        
        bookTitles.forEach(title => {
            if (title.scrollWidth > title.offsetWidth) {
                title.setAttribute('title', title.textContent);
            }
        });
        
        bookAuthors.forEach(author => {
            if (author.scrollWidth > author.offsetWidth) {
                author.setAttribute('title', author.textContent);
            }
        });
    });
</script>
</body>
</html>