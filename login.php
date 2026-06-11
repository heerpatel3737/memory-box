<?php
$page_title = "Login";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';

// Redirect if already logged in
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$message = "";
$is_error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login_id = trim($_POST['username'] ?? ''); // Can be email or username
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($login_id) || empty($password)) {
        $message = "Please fill in all fields.";
        $is_error = true;
    } else {
        $stmt = $conn->prepare("SELECT id, username, name, password_hash FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $login_id, $login_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_name'] = $user['name'];

                if ($remember) {
                    // Generate a token, store in DB and cookie (simplified version)
                    $token = bin2hex(random_bytes(32));
                    $expiry = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days
                    
                    $upd = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
                    $upd->bind_param("ssi", $token, $expiry, $user['id']);
                    $upd->execute();
                    
                    setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true); // HttpOnly
                }

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid password.";
                $is_error = true;
            }
        } else {
            $message = "User not found.";
            $is_error = true;
        }
        $stmt->close();
    }
}
?>

<div class="container" style="max-width: 500px; padding-top: 4rem; padding-bottom: 4rem;">
    <div class="card" style="border-top: 10px solid var(--secondary);">
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem; text-align: center;">Welcome Back 👋</h2>
        <p style="text-align: center; color: #64748b; margin-bottom: 2rem;">Log in to access your memories.</p>
        
        <?php if ($message): ?>
            <div style="background-color: <?php echo $is_error ? '#fef2f2' : '#f0fdf4'; ?>; border: var(--thick-border); border-color: <?php echo $is_error ? '#ef4444' : '#22c55e'; ?>; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; text-align: center;">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Username or Email</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="Enter your username" value="<?php echo isset($_POST['username']) ? e($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; font-weight: 600;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="remember" style="width: 18px; height: 18px; accent-color: var(--primary);"> Remember Me
                </label>
                <a href="#" style="color: var(--secondary); text-decoration: underline;">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 1rem;">
                Log In
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-weight: 600;">
            New to Memory Box? <a href="signup.php" style="color: var(--primary); text-decoration: underline;">Create an account</a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
