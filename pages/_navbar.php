<?php
// Include the database connection
include '../includes/db_connection.php';

// Handle the search query
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search_query'];
    $query = "SELECT * FROM books WHERE title LIKE '%$search_query%' OR author LIKE '%$search_query%' OR genre LIKE '%$search_query%' LIMIT 8";
    $result = mysqli_query($conn, $query);
} else {
    // Default query to fetch all books
    $result = null;
}

// Handle Delete Book
if (isset($_POST['delete_book'])) {
    $book_id = $_POST['book_id'];
    $query = "DELETE FROM books WHERE book_id='$book_id'";
}
?>

<!-- Navbar -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../css/navbar_styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* Enhanced Navbar Styles - Only Visual Improvements */
    .navbar-dark.bg-dark {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Brand with Logo */
    .navbar-brand {
        font-weight: 800 !important;
        font-size: 1.5rem;
        color: white !important;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 0;
        transition: all 0.3s ease;
    }
    
    .navbar-brand:hover {
        transform: translateY(-2px);
    }
    
    .brand-logo {
        width: 35px;
        height: 35px;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }
    
    /* Navigation Links Enhancement */
    .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        font-weight: 500;
        padding: 10px 20px !important;
        margin: 0 2px;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .navbar-nav .nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #4cc9f0, #4361ee);
        transition: width 0.3s ease;
    }
    
    .navbar-nav .nav-link:hover::after {
        width: 70%;
    }
    
    /* Search Bar Enhancement */
    .search-container {
        position: relative;
    }
    
    #search-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    #search-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    #search-input {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 2px solid transparent !important;
        border-radius: 10px !important;
        padding: 10px 15px !important;
        width: 250px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    #search-input:focus {
        border-color: #4361ee !important;
        box-shadow: 0 4px 20px rgba(67, 97, 238, 0.2) !important;
        transform: translateY(-1px);
    }
    
    #search-submit {
        background: linear-gradient(135deg, #4361ee, #4cc9f0) !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 10px 20px !important;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-left: 10px;
    }
    
    #search-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
    }
    
    /* Mobile Search */
    .d-lg-none .form-control {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 2px solid transparent !important;
        border-radius: 8px !important;
    }
    
    .d-lg-none .btn-outline-light {
        background: linear-gradient(135deg, #4361ee, #4cc9f0) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 6px 15px !important;
    }
    
    /* Logout Link Enhancement */
    .navbar-nav .nav-link[href*="logout"] {
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.2);
        color: #e74c3c !important;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .navbar-nav .nav-link[href*="logout"]:hover {
        background: rgba(231, 76, 60, 0.2);
        color: #c0392b !important;
    }
    
    /* Search Results Enhancement */
    .search-results {
        margin-top: 20px;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .search-results h3 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .search-card {
        background: #f8f9fa;
        border: none;
        border-radius: 12px;
        margin-bottom: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .search-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .search-card .card-body {
        padding: 20px;
    }
    
    .search-card .card-title {
        color: #2c3e50;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
    
    .search-card .card-text {
        color: #5d6d7e;
        margin-bottom: 5px;
        font-size: 0.95rem;
    }
    
    .search-card .status {
        font-weight: 600;
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 20px;
        display: inline-block;
        margin: 10px 0;
    }
    
    .text-success {
        background: rgba(46, 204, 113, 0.1);
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    .text-danger {
        background: rgba(231, 76, 60, 0.1);
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    .btn-group {
        margin-top: 10px;
    }
    
    .reserve-btn {
        background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .reserve-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
    }
    
    .delete-btn {
        background: linear-gradient(135deg, #c0392b, #e74c3c) !important;
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .delete-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
    }
    
    .no-results {
        text-align: center;
        padding: 40px;
        color: #7f8c8d;
    }
    
    .no-results p {
        font-size: 1.1rem;
    }
    
    /* Mobile Responsive */
    @media (max-width: 991px) {
        .navbar-nav {
            padding: 10px 0;
        }
        
        .navbar-nav .nav-link {
            margin: 5px 0;
        }
        
        .search-container {
            margin: 10px 0;
        }
    }
    
    /* Animation for search bar */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    #search-input, #search-submit {
        animation: slideIn 0.3s ease;
    }
</style>

<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Brand with Logo Image -->
        <?php if ($_SESSION['role'] == 'student') { ?>
            <a class="navbar-brand fw-bold" href="dashboard_student.php">
                <img src="../images/logo.png" alt="ULMS Logo" class="brand-logo">
                ULMS
            </a>
        <?php } ?>
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a class="navbar-brand fw-bold" href="dashboard_admin.php">
                <img src="../images/logo.png" alt="ULMS Logo" class="brand-logo">
                ULMS
            </a>
        <?php } ?>
        <?php if ($_SESSION['role'] == 'staff') { ?>
            <a class="navbar-brand fw-bold" href="dashboard_staff.php">
                <img src="../images/logo.png" alt="ULMS Logo" class="brand-logo">
                ULMS
            </a>
        <?php } ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($_SESSION['role'] == 'student') { ?>
                    <li class="nav-item"><a class="nav-link" href="books_student.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="reservations_student.php">Reservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="fines_student.php">Accumulated Fine</a></li>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <li class="nav-item"><a class="nav-link" href="books_admin.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="users_admin.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="reservations_admin.php">Reservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="fines_admin.php">Student Overdues</a></li>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'staff') { ?>
                    <li class="nav-item"><a class="nav-link" href="books_admin.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="reservations_admin.php">Reservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="fines_admin.php">Student Overdues</a></li>
                <?php } ?>
            </ul>

            <!-- Responsive Search Bar -->
            <div class="d-flex align-items-center">
                <form id="search-form-desktop" class="d-none d-lg-flex align-items-center" method="GET" action="<?= basename($_SERVER['PHP_SELF']); ?>">
                    <button class="btn btn-outline-light me-2" id="search-btn" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                    <input class="form-control border-0 bg-light d-none" id="search-input" type="text" name="search_query" placeholder="Search books..." value="<?= htmlspecialchars($search_query) ?>">
                    <button class="btn btn-outline-light d-none" id="search-submit" type="submit" name="search">Search</button>
                </form>
                <form class="d-flex d-lg-none justify-content-center w-100" method="GET" action="<?= basename($_SERVER['PHP_SELF']); ?>">
                    <input class="form-control form-control-sm me-2" type="text" name="search_query" placeholder="Search" value="<?= htmlspecialchars($search_query) ?>">
                    <button class="btn btn-outline-light btn-sm" type="submit" name="search">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Display Search Results -->
<?php if ($search_query && $result): ?>
    <div class="container mt-3">
        <h3>Search Results for "<?= htmlspecialchars($search_query) ?>"</h3>
        <div class="search-results">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($result)): ?>
                    <div class="search-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text"><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                            <p class="card-text"><strong>Genre:</strong> <?= htmlspecialchars($book['genre']) ?></p>
                            <p class="card-text"><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?></p>
                            <p class="status">
                                <?= ($book['reserved'] == 'Yes' ? '<span class="text-danger">Unavailable</span>' : '<span class="text-success">Available</span>') ?>
                            </p>
                            <div class="btn-group">
                                <?php if ($_SESSION['role'] == 'student' && $book['reserved'] == 'No'): ?>
                                    <form method="POST" action="books_student.php">
                                        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                        <button class="btn reserve-btn" type="submit" name="reserve_book">Reserve</button>
                                    </form>
                                <?php elseif ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff'): ?>
                                    <form method="POST" action="books_admin.php">
                                        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                        <button class="btn delete-btn" type="submit" name="delete_book">Delete</button>
                                    </form>
                                <?php elseif ($book['reserved'] == 'Yes'): ?>
                                    <span class="text-danger">Reserved</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No books found matching the search criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<!--JavaScript for Expandable Search Bar-->
<script>
    document.getElementById("search-btn").addEventListener("click", function () {
        const input = document.getElementById("search-input");
        const submit = document.getElementById("search-submit");
        if (input.classList.contains("d-none")) {
            input.classList.remove("d-none");
            submit.classList.remove("d-none");
            input.focus();
        } else if (input.value.trim() !== "") {
            document.getElementById("search-form-desktop").submit(); // Submit if input has text
        } else {
            input.classList.add("d-none");
            submit.classList.add("d-none");
        }
    });

    // Add smooth hover effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to nav links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Add click animation to search button
        const searchBtn = document.getElementById('search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        }
    });
</script>