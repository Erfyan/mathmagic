<?php
require_once __DIR__ . "/config.php";

// ==========================
// AUTOLOAD COMPONENTS
// ==========================
spl_autoload_register(function($class){
    $file = __DIR__ . "/libs/" . $class . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});

// ==========================
// HELPER
// ==========================
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function userRole() {
    return $_SESSION['role'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect("index.php?msg=login_required");
    }
}

function requireRole($role) {
    if (userRole() !== $role) {
        redirect("index.php?msg=forbidden");
    }
}
?>
