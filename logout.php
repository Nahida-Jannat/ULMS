<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Add cache control headers
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
    <script>
        // Clear browser history and prevent back navigation
        window.history.forward(1);
        
        // Replace current history entry with login page
        history.replaceState(null, null, 'index.php');
        
        // Prevent back button navigation
        history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            history.go(1);
        };
        
        // Redirect to login page
        setTimeout(function() {
            window.location.href = "index.php";
        }, 100);
    </script>
</head>
<body>
    <!-- Optional: Show logout message -->
    <p style="text-align: center; margin-top: 50px;">Logging out...</p>
</body>
</html>