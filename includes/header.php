<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/functions.php';

// Determine active page name for highlighted styling
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? e($page_title) . " | Memory Box 2.0" : "Memory Box 2.0 - Preserve Life's Chapters"; ?></title>
    
    <!-- Google Fonts Outfit & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Design System Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css_file): ?>
            <link rel="stylesheet" href="assets/css/<?php echo e($css_file); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <link rel="icon" href="assets/images/giftBox1.gif" type="image/x-icon">
</head>
<body>

    <!-- Nav Bar -->
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
            <img src="assets/images/giftBox1.gif" alt="Memory Box Icon">
            <span>Memory Box</span>
        </a>
        <div class="nav-links">
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
                <a href="drawers.php" class="nav-link <?php echo ($current_page == 'drawers.php') ? 'active' : ''; ?>">
                    <i class="fas fa-archive"></i> Drawers
                </a>
                <a href="timeline.php" class="nav-link <?php echo ($current_page == 'timeline.php') ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i> Timeline
                </a>
                <a href="letters.php" class="nav-link <?php echo ($current_page == 'letters.php') ? 'active' : ''; ?>">
                    <i class="fas fa-envelope-open-text"></i> Future Letters
                </a>
                <a href="confessions.php" class="nav-link <?php echo ($current_page == 'confessions.php') ? 'active' : ''; ?>">
                    <i class="fas fa-mask"></i> Confessions
                </a>
                <a href="profile.php" class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle"></i> Profile
                </a>
                <a href="logout.php" class="nav-link btn-nav">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php else: ?>
                <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    Home
                </a>
                <a href="login.php" class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                    Login
                </a>
                <a href="signup.php" class="nav-link btn-nav">
                    Sign Up
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content wrapper starts -->
    <div style="flex: 1;">
