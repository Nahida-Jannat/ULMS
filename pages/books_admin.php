<?php
session_start();

// Include the database connection
include '../includes/db_connection.php';

$alertMessage = ''; // Variable to hold alert message
$alertType = '';    // Variable to hold alert type (success or danger)

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../index.php");
    exit();
}

// Handle Add Book
if (isset($_POST['add_book'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $isbn = $_POST['isbn'];
    $reserved = 'No'; // New books are not reserved by default

    // Check if book_id already exists
    $check_book_id_query = "SELECT * FROM books WHERE book_id='$book_id'";
    $check_book_id_result = mysqli_query($conn, $check_book_id_query);

    // Check if isbn already exists
    $check_isbn_query = "SELECT * FROM books WHERE isbn='$isbn'";
    $check_isbn_result = mysqli_query($conn, $check_isbn_query);

    if (mysqli_num_rows($check_book_id_result) > 0) {
        $alertMessage = "Error: Book ID already exists!";
        $alertType = "danger";
    } elseif (mysqli_num_rows($check_isbn_result) > 0) {
        $alertMessage = "Error: ISBN already exists!";
        $alertType = "danger";
    } else {
        $query = "INSERT INTO books (book_id, title, author, genre, isbn, reserved) 
                  VALUES ('$book_id', '$title', '$author', '$genre', '$isbn', '$reserved')";
        if (mysqli_query($conn, $query)) {
            $alertMessage = "Book added successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Error adding book: " . mysqli_error($conn);
            $alertType = "danger";
        }
    }
}

// Handle Update Book
if (isset($_POST['update_book'])) {
    $original_book_id = $_POST['original_book_id'];
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $isbn = $_POST['isbn'];
    $reserved = $_POST['reserved'];

    // Check if book_id already exists (excluding the current record)
    $check_book_id_query = "SELECT * FROM books WHERE book_id='$book_id' AND book_id != '$original_book_id'";
    $check_book_id_result = mysqli_query($conn, $check_book_id_query);

    // Check if isbn already exists (excluding the current record)
    $check_isbn_query = "SELECT * FROM books WHERE isbn='$isbn' AND book_id != '$original_book_id'";
    $check_isbn_result = mysqli_query($conn, $check_isbn_query);

    if (mysqli_num_rows($check_book_id_result) > 0) {
        $alertMessage = "Error: Book ID already exists!";
        $alertType = "danger";
    } elseif (mysqli_num_rows($check_isbn_result) > 0) {
        $alertMessage = "Error: ISBN already exists!";
        $alertType = "danger";
    } else {
        $query = "UPDATE books 
                  SET book_id='$book_id', title='$title', author='$author', genre='$genre', isbn='$isbn', reserved='$reserved' 
                  WHERE book_id='$original_book_id'";
        if (mysqli_query($conn, $query)) {
            $alertMessage = "Book updated successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Error updating book: " . mysqli_error($conn);
            $alertType = "danger";
        }
    }
}

// Handle Delete Book
if (isset($_POST['delete_book'])) {
    $book_id = $_POST['original_book_id'];
    $query = "DELETE FROM books WHERE book_id='$book_id'";
    if (mysqli_query($conn, $query)) {
        $alertMessage = "Book deleted successfully!";
        $alertType = "success";
    } else {
        $alertMessage = "Error deleting book: " . mysqli_error($conn);
        $alertType = "danger";
    }
}

// Default query
$query = "SELECT * FROM books";

// Handle Search and Filter
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

// Get statistics
$total_books_query = "SELECT COUNT(*) as total FROM books";
$available_books_query = "SELECT COUNT(*) as available FROM books WHERE reserved = 'No'";
$reserved_books_query = "SELECT COUNT(*) as reserved FROM books WHERE reserved = 'Yes'";
$genres_count_query = "SELECT COUNT(DISTINCT genre) as genres FROM books";

$total_books = mysqli_fetch_assoc(mysqli_query($conn, $total_books_query))['total'] ?? 0;
$available_books = mysqli_fetch_assoc(mysqli_query($conn, $available_books_query))['available'] ?? 0;
$reserved_books = mysqli_fetch_assoc(mysqli_query($conn, $reserved_books_query))['reserved'] ?? 0;
$genres_count = mysqli_fetch_assoc(mysqli_query($conn, $genres_count_query))['genres'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Catalog Management - Library System</title>
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
        
        .stat-icon.available {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }
        
        .stat-icon.reserved {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.genres {
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
        
        .add-book-section {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-left: 6px solid var(--success-color);
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
        
        .add-book-form .form-group {
            margin-bottom: 1.2rem;
        }
        
        .add-book-form label {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
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
        
        .btn-submit {
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
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3);
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
        
        .manage-books-section {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
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
            padding: 1rem;
            vertical-align: middle;
            text-align: center;
            border: none;
        }
        
        .editable-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .editable-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .status-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }
        
        .status-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .badge-available {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(46, 204, 113, 0.3);
        }
        
        .badge-reserved {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
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
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .book-title {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        @media (max-width: 1200px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.8rem 0.5rem;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .books-container {
                padding: 1rem;
            }
            
            .header-section, .add-book-section, .search-section, .manage-books-section {
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

<div class="books-container">
    <div class="header-section">
        <h1><i class="fas fa-book"></i> Book Catalog Management</h1>
        <p class="subtitle">Manage your library's book collection, add new books, and update existing entries</p>
        
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
                <div class="stat-icon genres">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <h3 id="genres-count"><?php echo $genres_count; ?></h3>
                    <p>Genres</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Book Section -->
    <div class="add-book-section">
        <div class="section-header">
            <h2><i class="fas fa-plus-circle"></i> Add New Book</h2>
        </div>
        
        <form method="POST" class="add-book-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="book_id">Book ID</label>
                        <input type="number" class="form-control" name="book_id" required 
                               placeholder="Enter unique book ID">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Book Title</label>
                        <input type="text" class="form-control" name="title" required 
                               placeholder="Enter book title">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control" name="author" required 
                               placeholder="Enter author name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <input type="text" class="form-control" name="genre" required 
                               placeholder="Enter book genre">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" name="isbn" required 
                               placeholder="Enter ISBN number">
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-submit" name="add_book">
                        <i class="fas fa-plus"></i> Add New Book
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Search and Filter Section -->
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
                    <button type="submit" name="search" class="btn-submit" style="margin-top: 24px;">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Manage Books Section -->
    <div class="manage-books-section">
        <div class="section-header">
            <h2><i class="fas fa-edit"></i> Manage Books</h2>
            <div>
                <small class="text-muted">Showing <?php 
                    $count_result = mysqli_query($conn, $query);
                    $total_rows = mysqli_num_rows($count_result);
                    echo $total_rows; 
                ?> books</small>
            </div>
        </div>
        
        <?php
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover" id="booksTable">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>ISBN</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                $book_id = $row['book_id'];
                $status = $row['reserved'];
                $status_badge = $status === 'Yes' 
                    ? '<span class="badge-status badge-reserved">Reserved</span>'
                    : '<span class="badge-status badge-available">Available</span>';
                
                echo '<tr>
                    <form method="POST">
                        <td>
                            <input type="number" class="editable-input" name="book_id" value="' . $book_id . '" required>
                            <input type="hidden" name="original_book_id" value="' . $book_id . '">
                        </td>
                        <td><input type="text" class="editable-input" name="title" value="' . htmlspecialchars($row['title']) . '" required></td>
                        <td><input type="text" class="editable-input" name="author" value="' . htmlspecialchars($row['author']) . '" required></td>
                        <td><input type="text" class="editable-input" name="genre" value="' . htmlspecialchars($row['genre']) . '" required></td>
                        <td><input type="text" class="editable-input" name="isbn" value="' . htmlspecialchars($row['isbn']) . '" required></td>
                        <td>
                            <select name="reserved" class="status-select" required>
                                <option value="No" ' . ($status === 'No' ? 'selected' : '') . '>Available</option>
                                <option value="Yes" ' . ($status === 'Yes' ? 'selected' : '') . '>Reserved</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button type="submit" class="btn-action btn-update" name="update_book">
                                    <i class="fas fa-sync-alt"></i> Update
                                </button>
                                <button type="submit" class="btn-action btn-delete" name="delete_book" 
                                        onclick="return confirm(\'Are you sure you want to delete this book?\')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </td>
                    </form>
                </tr>';
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
                    ? 'No books match your search criteria. Try a different search.' 
                    : 'There are no books in the catalog yet. Add your first book above!') . '</p>
            </div>';
        }
        ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Book Catalog Management</p>
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
        const tableRows = document.querySelectorAll('#booksTable tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 100 * index);
        });
        
        // Add hover effects to editable inputs
        const editableInputs = document.querySelectorAll('.editable-input, .status-select');
        editableInputs.forEach(input => {
            input.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 0 0 2px rgba(52, 152, 219, 0.2)';
            });
            
            input.addEventListener('mouseleave', function() {
                if (!this.matches(':focus')) {
                    this.style.boxShadow = 'none';
                }
            });
            
            input.addEventListener('focus', function() {
                this.style.boxShadow = '0 0 0 3px rgba(52, 152, 219, 0.1)';
            });
            
            input.addEventListener('blur', function() {
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
</body>
</html>