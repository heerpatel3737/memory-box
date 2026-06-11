<?php
$page_title = "Sign Up";
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
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $message = "All fields are required!";
        $is_error = true;
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or Email already exists!";
            $is_error = true;
        } else {
            $stmt->close();
            
            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, username, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $username, $hashedPassword);
            
            if ($stmt->execute()) {
                // Auto-login the user
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_name'] = $name;
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Registration failed. Please try again.";
                $is_error = true;
            }
        }
        $stmt->close();
    }
}
?>

<div class="container" style="max-width: 500px; padding-top: 4rem; padding-bottom: 4rem;">
    <div class="card" style="border-top: 10px solid var(--primary);">
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem; text-align: center;">Join Memory Box 🎁</h2>
        <p style="text-align: center; color: #64748b; margin-bottom: 2rem;">Create your secure vault today.</p>
        
        <?php if ($message): ?>
            <div style="background-color: <?php echo $is_error ? '#fef2f2' : '#f0fdf4'; ?>; border: var(--thick-border); border-color: <?php echo $is_error ? '#ef4444' : '#22c55e'; ?>; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; text-align: center;">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="John Doe" value="<?php echo isset($_POST['name']) ? e($_POST['name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="john@example.com" value="<?php echo isset($_POST['email']) ? e($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="johndoe123" value="<?php echo isset($_POST['username']) ? e($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Create a strong password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 1rem;">
                Create Account
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-weight: 600;">
            Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: underline;">Log in here</a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>