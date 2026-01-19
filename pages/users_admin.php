<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$alertMessage = '';
$alertType = '';

// Handle Add User
if (isset($_POST['add_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Store as plain text
    $role = $_POST['role'];

    // Check if user_id already exists
    $checkQuery = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $alertMessage = "Error: User ID already exists!";
        $alertType = "danger";
    } else {
        $query = "INSERT INTO users (user_id, name, email, password, role) VALUES ('$user_id', '$name', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            $alertMessage = "User added successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Error adding user: " . mysqli_error($conn);
            $alertType = "danger";
        }
    }
}

// Handle Update User
if (isset($_POST['update_user'])) {
    $original_user_id = $_POST['original_user_id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password_input = $_POST['password'];
    $role = $_POST['role'];
    
    // If password field is not empty and not the masked value
    if (!empty($password_input) && $password_input !== "********") {
        // Store as plain text (no hashing)
        $password = $password_input;
        $query = "UPDATE users SET user_id='$user_id', name='$name', email='$email', password='$password', role='$role' WHERE user_id='$original_user_id'";
    } else {
        // If password field is empty or masked, keep existing password
        $query = "UPDATE users SET user_id='$user_id', name='$name', email='$email', role='$role' WHERE user_id='$original_user_id'";
    }
    
    if (mysqli_query($conn, $query)) {
        $alertMessage = "User updated successfully!";
        $alertType = "success";
    } else {
        $alertMessage = "Error updating user: " . mysqli_error($conn);
        $alertType = "danger";
    }
}

// Handle Delete User
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['original_user_id'];
    
    $checkRoleQuery = "SELECT role FROM users WHERE user_id='$user_id'";
    $roleResult = mysqli_query($conn, $checkRoleQuery);
    
    if ($roleResult && mysqli_num_rows($roleResult) > 0) {
        $user = mysqli_fetch_assoc($roleResult);
        
        if ($user['role'] === 'admin') {
            $alertMessage = "Error: Cannot delete admin accounts!";
            $alertType = "danger";
        } else {
            $query = "DELETE FROM users WHERE user_id='$user_id'";
            if (mysqli_query($conn, $query)) {
                $alertMessage = "User deleted successfully!";
                $alertType = "success";
            } else {
                $alertMessage = "Error deleting user: " . mysqli_error($conn);
                $alertType = "danger";
            }
        }
    } else {
        $alertMessage = "Error: User not found!";
        $alertType = "danger";
    }
}

// Handle Search and Filter
$searchQuery = '';
$roleFilter = '';
$genres_result = mysqli_query($conn, "SELECT DISTINCT role FROM users");

if (isset($_POST['search'])) {
    $searchQuery = $_POST['search_query'];
    $roleFilter = $_POST['role_filter'];

    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
    $roleFilter = mysqli_real_escape_string($conn, $roleFilter);

    $sql = "SELECT * FROM users WHERE 
            (user_id LIKE '%$searchQuery%' OR name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%')";

    if ($roleFilter != '') {
        $sql .= " AND role='$roleFilter'";
    }

    $searchResults = mysqli_query($conn, $sql);
} else {
    $searchResults = mysqli_query($conn, "SELECT * FROM users");
}

// Get user statistics
$total_users_query = "SELECT COUNT(*) as total FROM users";
$admin_users_query = "SELECT COUNT(*) as admins FROM users WHERE role = 'admin'";
$staff_users_query = "SELECT COUNT(*) as staff FROM users WHERE role = 'staff'";
$student_users_query = "SELECT COUNT(*) as students FROM users WHERE role = 'student'";

$total_users = mysqli_fetch_assoc(mysqli_query($conn, $total_users_query))['total'] ?? 0;
$admin_users = mysqli_fetch_assoc(mysqli_query($conn, $admin_users_query))['admins'] ?? 0;
$staff_users = mysqli_fetch_assoc(mysqli_query($conn, $staff_users_query))['staff'] ?? 0;
$student_users = mysqli_fetch_assoc(mysqli_query($conn, $student_users_query))['students'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"/>
    <style>
        :root {
            --primary-color: #4480bb;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #2ecc71;
            --info-color: #1abc9c;
            --light-color: #ecf0f1;
            --dark-color: #335779;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
        }
        
        .users-container {
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
        
        .stat-icon.admin {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }
        
        .stat-icon.staff {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.student {
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
        
        .add-user-section {
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
        
        .add-user-form .form-group {
            margin-bottom: 1.2rem;
        }
        
        .add-user-form label {
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
        
        .manage-users-section {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
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
        
        .role-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }
        
        .role-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .badge-role {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .badge-admin {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            border: 1px solid rgba(231, 76, 60, 0.3);
        }
        
        .badge-staff {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }
        
        .badge-student {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(46, 204, 113, 0.3);
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
        
        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
            background-color: #95a5a6 !important;
        }
        
        .alert-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        .no-users {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        
        .no-users i {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }
        
        .no-users h3 {
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
        
        .user-name {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .password-field {
            font-family: monospace;
            letter-spacing: 1px;
        }
        
        .password-input-wrapper {
            position: relative;
            width: 100%;
        }
        
        .password-toggle-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            padding: 4px 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .password-toggle-btn:hover {
            color: var(--secondary-color);
            transform: translateY(-50%) scale(1.1);
        }
        
        .password-hint {
            font-size: 0.75rem;
            color: #95a5a6;
            margin-top: 4px;
            text-align: left;
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
            .users-container {
                padding: 1rem;
            }
            
            .header-section, .add-user-section, .search-section, .manage-users-section {
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

<!-- Navbar -->
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

<div class="users-container">
    <div class="header-section">
        <h1><i class="fas fa-users-cog"></i> User Management</h1>
        <p class="subtitle">Manage library users, assign roles, and control access permissions</p>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-users"><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon admin">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-info">
                    <h3 id="admin-users"><?php echo $admin_users; ?></h3>
                    <p>Admins</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon staff">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-info">
                    <h3 id="staff-users"><?php echo $staff_users; ?></h3>
                    <p>Staff Members</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon student">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3 id="student-users"><?php echo $student_users; ?></h3>
                    <p>Students</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add User Section -->
    <div class="add-user-section">
        <div class="section-header">
            <h2><i class="fas fa-user-plus"></i> Add New User</h2>
        </div>
        
        <form method="POST" class="add-user-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id">User ID</label>
                        <input type="number" class="form-control" name="user_id" required 
                               placeholder="Enter unique user ID">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="name" required 
                               placeholder="Enter user's full name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" name="email" required 
                               placeholder="Enter email address">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="new_password" name="password" required 
                                   placeholder="Enter password">
                            <button type="button" class="password-toggle-btn" onclick="toggleNewPassword()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role">User Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="staff">Library Staff</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-submit" name="add_user">
                        <i class="fas fa-user-plus"></i> Add New User
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
                    <label for="search_query" class="form-label">Search Users</label>
                    <input type="text" name="search_query" class="form-control" 
                           placeholder="Search by ID, name, or email" 
                           value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="form-group">
                    <label for="role_filter" class="form-label">Filter by Role</label>
                    <select name="role_filter" class="form-control">
                        <option value="">All Roles</option>
                        <?php 
                        mysqli_data_seek($genres_result, 0);
                        while ($role = mysqli_fetch_assoc($genres_result)): ?>
                            <option value="<?php echo htmlspecialchars($role['role']); ?>"
                                <?php echo $roleFilter === $role['role'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['role']); ?>
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
    
    <!-- Manage Users Section -->
    <div class="manage-users-section">
        <div class="section-header">
            <h2><i class="fas fa-user-edit"></i> Manage Users</h2>
            <div>
                <small class="text-muted">Showing 
                    <?php 
                    $count_result = mysqli_query($conn, 
                        isset($_POST['search']) ? $sql : "SELECT * FROM users"
                    );
                    $total_rows = mysqli_num_rows($count_result);
                    echo $total_rows; 
                    ?> users
                </small>
            </div>
        </div>
        
        <?php
        $result = mysqli_query($conn, isset($_POST['search']) ? $sql : "SELECT * FROM users");
        
        if (mysqli_num_rows($result) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                $user_id = $row['user_id'];
                $role = $row['role'];
                $is_admin = $role === 'admin';
                
                echo '<tr>
                    <form method="POST">
                        <td>
                            <input type="number" class="editable-input" name="user_id" value="' . $user_id . '" required>
                            <input type="hidden" name="original_user_id" value="' . $user_id . '">
                        </td>
                        <td><input type="text" class="editable-input" name="name" value="' . htmlspecialchars($row['name']) . '" required></td>
                        <td><input type="email" class="editable-input" name="email" value="' . htmlspecialchars($row['email']) . '" required></td>
                        <td>
                            <div class="password-input-wrapper">
                                <input type="password" class="editable-input password-field" name="password" 
                                       value="' . htmlspecialchars($row['password']) . '"
                                       placeholder="Enter new password"
                                       id="password_' . $user_id . '">
                                <button type="button" class="password-toggle-btn" onclick="togglePassword(' . $user_id . ')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-hint">Enter new password or leave as is to keep current</div>
                        </td>
                        <td>
                            <select name="role" class="role-select" required>
                                <option value="admin" ' . ($role === 'admin' ? 'selected' : '') . '>Administrator</option>
                                <option value="staff" ' . ($role === 'staff' ? 'selected' : '') . '>Library Staff</option>
                                <option value="student" ' . ($role === 'student' ? 'selected' : '') . '>Student</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button type="submit" class="btn-action btn-update" name="update_user">
                                    <i class="fas fa-sync-alt"></i> Update
                                </button>
                                <button type="submit" class="btn-action btn-delete" name="delete_user" 
                                        ' . ($is_admin ? 'disabled' : '') . '
                                        ' . (!$is_admin ? 'onclick="return confirm(\'Are you sure you want to delete this user?\')"' : '') . '>
                                    <i class="fas fa-trash-alt"></i> ' . ($is_admin ? 'Protected' : 'Delete') . '
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
            <div class="no-users">
                <i class="fas fa-user-slash"></i>
                <h3>No Users Found</h3>
                <p>' . (!empty($searchQuery) || !empty($roleFilter) 
                    ? 'No users match your search criteria. Try a different search.' 
                    : 'There are no users in the system yet. Add your first user above!') . '</p>
            </div>';
        }
        ?>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | User Management Module</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password for new user form
    function toggleNewPassword() {
        const passwordField = document.getElementById('new_password');
        const toggleBtn = event.currentTarget;
        const icon = toggleBtn.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            passwordField.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
    
    // Toggle password for existing users
    function togglePassword(userId) {
        const passwordField = document.getElementById('password_' + userId);
        const toggleBtn = event.currentTarget;
        const icon = toggleBtn.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            passwordField.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
        
        // Add animation to table rows
        const tableRows = document.querySelectorAll('#usersTable tbody tr');
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
        const editableInputs = document.querySelectorAll('.editable-input, .role-select');
        editableInputs.forEach(input => {
            input.addEventListener('mouseenter', function() {
                if (!this.matches(':focus')) {
                    this.style.boxShadow = '0 0 0 2px rgba(52, 152, 219, 0.1)';
                }
            });
            
            input.addEventListener('mouseleave', function() {
                if (!this.matches(':focus')) {
                    this.style.boxShadow = 'none';
                }
            });
            
            input.addEventListener('focus', function() {
                this.style.boxShadow = '0 0 0 3px rgba(52, 152, 219, 0.1)';
                this.style.backgroundColor = '#f8f9fa';
            });
            
            input.addEventListener('blur', function() {
                this.style.boxShadow = 'none';
                this.style.backgroundColor = 'white';
            });
        });
        
        // Add tooltip for disabled delete buttons
        const deleteButtons = document.querySelectorAll('.btn-delete:disabled');
        deleteButtons.forEach(button => {
            button.title = "Admin accounts cannot be deleted";
            button.style.cursor = 'not-allowed';
            
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-text';
            tooltip.textContent = 'Admin accounts are protected from deletion';
            tooltip.style.position = 'absolute';
            tooltip.style.backgroundColor = 'var(--primary-color)';
            tooltip.style.color = 'white';
            tooltip.style.padding = '5px 10px';
            tooltip.style.borderRadius = '5px';
            tooltip.style.fontSize = '12px';
            tooltip.style.zIndex = '100';
            tooltip.style.display = 'none';
            tooltip.style.bottom = '100%';
            tooltip.style.left = '50%';
            tooltip.style.transform = 'translateX(-50%)';
            tooltip.style.whiteSpace = 'nowrap';
            
            button.parentElement.style.position = 'relative';
            button.parentElement.appendChild(tooltip);
            
            button.addEventListener('mouseenter', function() {
                tooltip.style.display = 'block';
            });
            
            button.addEventListener('mouseleave', function() {
                tooltip.style.display = 'none';
            });
        });
    });
</script>
</body>
</html>