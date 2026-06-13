<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session storage variables
$_SESSION = array();

// Completely destroy the session cookie connection
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Kill the server session state
session_destroy();

// Redirect back to the login page cleanly
header("Location: loginAD.php");
exit();
?>