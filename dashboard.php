<?php
$page_title = "Dashboard";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth(); // Ensure user is logged in

$user_id = $_SESSION['user_id'];
$user_name = explode(' ', $_SESSION['user_name'])[0]; // First name

// Time-based greeting
$hour = date('H');
$greeting = "Good Evening";
if ($hour < 12) { $greeting = "Good Morning"; }
elseif ($hour < 17) { $greeting = "Good Afternoon"; }

// Aggregate Stats (mocked defaults if no data)
$stats = [
    'total_memories' => 0,
    'total_photos' => 0,
    'future_letters' => 0,
    'favorites' => 0
];

// Query for Total Memories & Favorites
$res = $conn->query("SELECT COUNT(*) as total, SUM(is_favorite) as favs FROM memories WHERE user_id = $user_id");
if ($row = $res->fetch_assoc()) {
    $stats['total_memories'] = $row['total'] ?? 0;
    $stats['favorites'] = $row['favs'] ?? 0;
}

// Query for Total Photos
$res = $conn->query("SELECT COUNT(*) as photo_total FROM memory_photos mp JOIN memories m ON mp.memory_id = m.id WHERE m.user_id = $user_id");
if ($row = $res->fetch_assoc()) {
    $stats['total_photos'] = $row['photo_total'] ?? 0;
}

// Query for Future Letters
$res = $conn->query("SELECT COUNT(*) as letters FROM future_letters WHERE user_id = $user_id");
if ($row = $res->fetch_assoc()) {
    $stats['future_letters'] = $row['letters'] ?? 0;
}

// Get recent memories
$recent_memories = [];
$res = $conn->query("SELECT * FROM memories WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 4");
while ($row = $res->fetch_assoc()) {
    $recent_memories[] = $row;
}
?>

<div class="container" style="padding-top: 3rem;">
    <!-- Greeting Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2.8rem; margin-bottom: 0.5rem;"><?php echo $greeting; ?>, <span style="color: var(--primary);"><?php echo e($user_name); ?></span>!</h1>
            <p style="color: #64748b; font-size: 1.1rem; font-weight: 600;">Welcome back to your personal memory vault.</p>
        </div>
        <div>
            <a href="memory-editor.php" class="btn btn-primary" style="font-size: 1.1rem;"><i class="fas fa-plus"></i> Add Memory</a>
        </div>
    </div>

    <!-- Stats Ribbon -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 4rem;">
        
        <div class="card" style="background-color: var(--primary); color: white; border-color: var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem; opacity: 0.9;">Total Memories</p>
                    <h2 style="font-size: 3rem; margin: 0; line-height: 1;"><?php echo $stats['total_memories']; ?></h2>
                </div>
                <div style="font-size: 3rem; opacity: 0.5;"><i class="fas fa-book"></i></div>
            </div>
        </div>

        <div class="card" style="background-color: var(--secondary); color: var(--fg-main); border-color: var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem; opacity: 0.9;">Photos Saved</p>
                    <h2 style="font-size: 3rem; margin: 0; line-height: 1;"><?php echo $stats['total_photos']; ?></h2>
                </div>
                <div style="font-size: 3rem; opacity: 0.5;"><i class="fas fa-camera-retro"></i></div>
            </div>
        </div>

        <div class="card" style="background-color: var(--tertiary); color: var(--fg-main); border-color: var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem; opacity: 0.9;">Future Letters</p>
                    <h2 style="font-size: 3rem; margin: 0; line-height: 1;"><?php echo $stats['future_letters']; ?></h2>
                </div>
                <div style="font-size: 3rem; opacity: 0.5;"><i class="fas fa-envelope-open-text"></i></div>
            </div>
        </div>

        <div class="card" style="background-color: var(--quaternary); color: var(--fg-main); border-color: var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem; opacity: 0.9;">Favorites</p>
                    <h2 style="font-size: 3rem; margin: 0; line-height: 1;"><?php echo $stats['favorites']; ?></h2>
                </div>
                <div style="font-size: 3rem; opacity: 0.5;"><i class="fas fa-star"></i></div>
            </div>
        </div>

    </div>

    <!-- Main Dashboard Split -->
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 3rem;">
        
        <!-- Recent Memories -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 2rem;">Recent Memories</h2>
                <a href="timeline.php" style="color: var(--primary); font-weight: 700; text-decoration: underline;">View Timeline</a>
            </div>
            
            <?php if (empty($recent_memories)): ?>
                <div class="card" style="text-align: center; padding: 4rem 2rem; border-style: dashed; border-width: 4px; background-color: transparent; box-shadow: none;">
                    <div style="font-size: 4rem; margin-bottom: 1rem; color: #cbd5e1;">📸</div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #64748b;">It's a little empty here!</h3>
                    <p style="margin-bottom: 2rem; color: #94a3b8; font-weight: 500;">Start filling your memory box by capturing your first moment.</p>
                    <a href="memory-editor.php" class="btn btn-tertiary">Create First Memory</a>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($recent_memories as $mem): ?>
                        <div class="card random-rotate" style="padding: 1rem; cursor: pointer;" onclick="location.href='memory-view.php?id=<?php echo $mem['id']; ?>'">
                            <div style="width: 100%; height: 180px; background-color: #f1f5f9; border-radius: 12px; border: 2px solid var(--border-color); margin-bottom: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
                                <?php if (!empty($mem['cover_image'])): ?>
                                    <img src="<?php echo e($mem['cover_image']); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Cover">
                                <?php else: ?>
                                    <span style="font-size: 3rem;">🖼️</span>
                                <?php endif; ?>
                                
                                <span class="badge" style="position: absolute; top: 10px; right: 10px; background-color: var(--card-white);">
                                    <?php echo e($mem['category']); ?>
                                </span>
                            </div>
                            <h3 style="font-size: 1.4rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo e($mem['title']); ?></h3>
                            <p style="color: #64748b; font-size: 0.95rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($mem['event_date'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Sidebar -->
        <div>
            <div class="card" style="margin-bottom: 2rem; background-color: var(--card-white); padding: 2rem;">
                <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-fire" style="color: #ef4444;"></i> Streaks & Activity
                </h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: var(--thick-border); border-radius: 12px; background-color: #f8fafc;">
                        <span style="font-weight: 700;">This Month</span>
                        <span class="badge" style="background-color: var(--quaternary); color: white;">+4 Memories</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: var(--thick-border); border-radius: 12px; background-color: #f8fafc;">
                        <span style="font-weight: 700;">Top Mood</span>
                        <span class="badge" style="background-color: var(--tertiary); color: var(--fg-main);">😊 Happy</span>
                    </div>
                </div>
            </div>

            <div class="card" style="background-color: var(--primary); color: white; padding: 2rem;">
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; -webkit-text-stroke: 1px var(--border-color);">Need to vent?</h3>
                <p style="margin-bottom: 1.5rem; font-weight: 500; opacity: 0.9;">Got something on your mind? Drop it into the locked confessions box.</p>
                <a href="confessions.php" class="btn btn-tertiary" style="width: 100%;">Unlock Confessions 🤐</a>
            </div>
        </div>

    </div>
</div>

<!-- Responsive grid fix for smaller screens -->
<style>
@media (max-width: 992px) {
    .container > div:nth-of-type(3) {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
