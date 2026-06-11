<?php
$page_title = "Future Letters";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$user_id = $_SESSION['user_id'];
$message = "";
$is_error = false;

// Handle Letter Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $recipient = trim($_POST['recipient'] ?? 'Future Me');
    $unlock_date = $_POST['unlock_date'] ?? '';

    if (empty($title) || empty($content) || empty($unlock_date)) {
        $message = "Title, content, and unlock date are required.";
        $is_error = true;
    } elseif (strtotime($unlock_date) <= time()) {
        $message = "Unlock date must be in the future!";
        $is_error = true;
    } else {
        $stmt = $conn->prepare("INSERT INTO future_letters (user_id, title, content, recipient, unlock_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $content, $recipient, $unlock_date);
        if ($stmt->execute()) {
            header("Location: letters.php?success=1");
            exit();
        } else {
            $message = "Failed to save letter.";
            $is_error = true;
        }
        $stmt->close();
    }
}

// Fetch all letters
$letters = [];
$res = $conn->query("SELECT * FROM future_letters WHERE user_id = $user_id ORDER BY unlock_date ASC");
while($row = $res->fetch_assoc()) {
    $letters[] = $row;
}
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 class="page-title">Future Letters Vault 💌</h1>
        <p class="page-subtitle">Write a letter today. Read it when the time is right.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background-color: #f0fdf4; border: var(--thick-border); border-color: #22c55e; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 600; text-align: center;">
            Letter successfully sealed and sent to the future! 🚀
        </div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div style="background-color: #fef2f2; border: var(--thick-border); border-color: #ef4444; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 600; text-align: center;">
            <?php echo e($message); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 3rem;">
        
        <!-- Left: List of Letters -->
        <div>
            <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem;">Your Time Capsules</h2>
            
            <?php if (empty($letters)): ?>
                <div class="card" style="text-align: center; padding: 3rem; border-style: dashed; background-color: transparent;">
                    <div style="font-size: 3rem; color: #cbd5e1;">📪</div>
                    <p style="color: #64748b; font-weight: 500; margin-top: 1rem;">No letters currently in the vault.</p>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php 
                    $today = date('Y-m-d');
                    foreach ($letters as $letter): 
                        $is_locked = ($letter['unlock_date'] > $today);
                    ?>
                        <div class="card" style="display: flex; gap: 1.5rem; align-items: center; <?php echo $is_locked ? 'background-color: #f8fafc; opacity: 0.8;' : 'border-left: 8px solid var(--secondary);'; ?>">
                            
                            <div style="font-size: 3rem; color: <?php echo $is_locked ? '#94a3b8' : 'var(--secondary)'; ?>;">
                                <?php echo $is_locked ? '🔒' : '✉️'; ?>
                            </div>
                            
                            <div style="flex: 1;">
                                <h3 style="font-size: 1.4rem; margin-bottom: 0.25rem;">
                                    <?php echo e($letter['title']); ?>
                                </h3>
                                <p style="color: #64748b; font-size: 0.95rem; font-weight: 600;">
                                    To: <?php echo e($letter['recipient']); ?>
                                </p>
                            </div>
                            
                            <div style="text-align: right;">
                                <?php if ($is_locked): ?>
                                    <div class="badge" style="background-color: #e2e8f0; color: #475569;">
                                        Unlocks: <?php echo date('M d, Y', strtotime($letter['unlock_date'])); ?>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="alert('Letter contents:\n\n<?php echo htmlspecialchars(addslashes($letter['content'])); ?>')">
                                        Tear Open
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Create Letter Form -->
        <div>
            <div class="card" style="border-top: 10px solid var(--quaternary); position: sticky; top: 100px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Seal a new letter</h2>
                
                <form action="letters.php" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required placeholder="Dear future me...">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="recipient">Recipient</label>
                        <input type="text" id="recipient" name="recipient" class="form-control" value="Future Me" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="unlock_date">Unlock Date</label>
                        <input type="date" id="unlock_date" name="unlock_date" class="form-control" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Message</label>
                        <textarea id="content" name="content" class="form-control" rows="5" required placeholder="Write your message here..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-quaternary" style="width: 100%;"><i class="fas fa-lock"></i> Seal & Lock</button>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
@media (max-width: 800px) {
    .container > div:nth-child(3) {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
