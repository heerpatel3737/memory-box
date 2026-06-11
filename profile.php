<?php
$page_title = "My Profile";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$user_id = $_SESSION['user_id'];
$user = get_current_user_details();

// Get memory count
$res = $conn->query("SELECT COUNT(*) as total FROM memories WHERE user_id = $user_id");
$total_memories = $res->fetch_assoc()['total'];
?>

<div class="container" style="max-width: 600px; padding-top: 4rem; padding-bottom: 5rem;">
    <div class="card" style="text-align: center; padding: 4rem 2rem; border-top: 15px solid var(--primary);">
        
        <div style="width: 120px; height: 120px; border-radius: 50%; background-color: var(--tertiary); margin: 0 auto 1.5rem auto; border: 4px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 4rem; overflow: hidden;">
            <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'assets/images/default-avatar.svg'): ?>
                <img src="<?php echo e($user['profile_photo']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                🤠
            <?php endif; ?>
        </div>

        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo e($user['name']); ?></h1>
        <p style="color: #64748b; font-size: 1.1rem; font-weight: 600; margin-bottom: 2rem;">@<?php echo e($user['username']); ?></p>

        <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 3rem;">
            <div>
                <h3 style="font-size: 2rem; color: var(--primary);"><?php echo $total_memories; ?></h3>
                <p style="font-weight: 700; color: #64748b;">Memories</p>
            </div>
            <div>
                <h3 style="font-size: 2rem; color: var(--secondary);"><?php echo date('Y', strtotime($user['created_at'])); ?></h3>
                <p style="font-weight: 700; color: #64748b;">Joined</p>
            </div>
        </div>

        <div style="background-color: #f8fafc; border: var(--thick-border); border-radius: 12px; padding: 2rem; text-align: left;">
            <h3 style="font-size: 1.3rem; margin-bottom: 1rem;"><i class="fas fa-user-edit"></i> Account Details</h3>
            
            <p style="margin-bottom: 0.5rem;"><strong>Email:</strong> <?php echo e($user['email']); ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Bio:</strong> <?php echo $user['bio'] ? e($user['bio']) : '<em style="color:#94a3b8;">No bio written yet.</em>'; ?></p>
            
            <button class="btn btn-secondary" style="width: 100%; margin-top: 1.5rem;" onclick="alert('Profile editing module coming soon!')">
                Edit Profile
            </button>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
