<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * XSS Escaping Helper
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Check if the user is authenticated, otherwise redirect
 */
function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        // Check remember me cookie
        if (isset($_COOKIE['remember_token']) && !empty($_COOKIE['remember_token'])) {
            include_once __DIR__ . '/db.php';
            global $conn;
            $token = $_COOKIE['remember_token'];
            $stmt = $conn->prepare("SELECT id, username, name FROM users WHERE reset_token = ? AND reset_expires > NOW()");
            if ($stmt) {
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($user = $res->fetch_assoc()) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_name'] = $user['name'];
                    return;
                }
            }
        }
        
        header("Location: login.php");
        exit();
    }
}

/**
 * Check if user is logged in (returns boolean)
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged in user details
 */
function get_current_user_details() {
    if (!is_logged_in()) return null;
    
    include_once __DIR__ . '/db.php';
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Upload Multiple Photos Helper
 */
function upload_photos($files, $memory_id) {
    include_once __DIR__ . '/db.php';
    global $conn;
    
    $target_dir = __DIR__ . "/../assets/uploads/memories/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $uploaded_paths = [];
    
    // Check if files array is set and not empty
    if (!isset($files['name']) || empty($files['name'][0])) {
        return $uploaded_paths;
    }
    
    $count = count($files['name']);
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $tmp_name = $files['tmp_name'][$i];
            $original_name = basename($files['name'][$i]);
            $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            
            // Validate extension
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed_exts)) {
                $new_filename = uniqid('img_', true) . '.' . $ext;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $web_path = "assets/uploads/memories/" . $new_filename;
                    
                    // Insert path in database
                    $stmt = $conn->prepare("INSERT INTO memory_photos (memory_id, photo_path) VALUES (?, ?)");
                    $stmt->bind_param("is", $memory_id, $web_path);
                    $stmt->execute();
                    $stmt->close();
                    
                    $uploaded_paths[] = $web_path;
                }
            }
        }
    }
    return $uploaded_paths;
}
?>
