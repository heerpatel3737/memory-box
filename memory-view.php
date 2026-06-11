<?php
$page_title = "Scrapbook View";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$memory_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Fetch the memory
$stmt = $conn->prepare("SELECT * FROM memories WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $memory_id, $user_id);
$stmt->execute();
$memory = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$memory) {
    echo "<div class='container' style='padding-top: 5rem;'><h1 style='text-align:center;'>Memory not found! 😢</h1><div style='text-align:center; margin-top:2rem;'><a href='dashboard.php' class='btn btn-primary'>Back Home</a></div></div>";
    include_once __DIR__ . '/includes/footer.php';
    exit();
}

// Fetch additional photos
$photos = [];
$photo_stmt = $conn->prepare("SELECT photo_path FROM memory_photos WHERE memory_id = ?");
$photo_stmt->bind_param("i", $memory_id);
$photo_stmt->execute();
$photo_res = $photo_stmt->get_result();
while($p = $photo_res->fetch_assoc()) {
    $photos[] = $p['photo_path'];
}
$photo_stmt->close();

// Fetch tags
$tags = [];
$tag_stmt = $conn->prepare("SELECT tag_name FROM memory_tags WHERE memory_id = ?");
$tag_stmt->bind_param("i", $memory_id);
$tag_stmt->execute();
$tag_res = $tag_stmt->get_result();
while($t = $tag_res->fetch_assoc()) {
    $tags[] = $t['tag_name'];
}
$tag_stmt->close();
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem; max-width: 1000px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="memories.php?category=<?php echo urlencode($memory['category']); ?>" class="btn btn-tertiary">
            <i class="fas fa-arrow-left"></i> Back to <?php echo e($memory['category']); ?>
        </a>
        <button class="btn btn-secondary" onclick="alert('Editing coming soon!')"><i class="fas fa-edit"></i> Edit</button>
    </div>

    <!-- The Digital Scrapbook Canvas -->
    <div class="card" style="background-color: #fcf9f2; background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h40v40H0V0zm20 20h20v20H20V20zM0 20h20v20H0V20zM20 0h20v20H20V0z\' fill=\'%23f5eedc\' fill-opacity=\'0.4\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); padding: 3rem; border-radius: 4px; box-shadow: 15px 15px 0px rgba(0,0,0,0.1); border: var(--thick-border);">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            
            <!-- Left Page: The Written Entry -->
            <div style="background-color: white; border: 1px solid #e2e8f0; padding: 2.5rem; position: relative; box-shadow: 4px 4px 10px rgba(0,0,0,0.05); background-image: repeating-linear-gradient(transparent, transparent 29px, #e2e8f0 30px); background-attachment: local; line-height: 30px;">
                
                <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 80px; height: 30px; background-color: rgba(0,0,0,0.1); border-radius: 4px; backdrop-filter: blur(2px);"></div>
                
                <h1 style="font-family: var(--font-hand); font-size: 3rem; margin-top: 1rem; margin-bottom: 1rem; color: var(--fg-main); transform: rotate(-2deg);"><?php echo e($memory['title']); ?></h1>
                
                <div style="font-family: var(--font-hand); font-size: 1.6rem; color: #334155; white-space: pre-wrap; margin-bottom: 2rem; min-height: 200px;"><?php echo e($memory['description']); ?></div>
                
                <div style="margin-top: 2rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <span class="badge" style="background-color: var(--tertiary);"><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($memory['event_date'])); ?></span>
                    <span class="badge" style="background-color: var(--primary); color: white;"><?php echo e($memory['mood']); ?></span>
                    <?php foreach($tags as $tag): ?>
                        <span class="badge" style="background-color: var(--bg-page);">#<?php echo e($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Page: Polaroid Collage -->
            <div style="position: relative; padding: 1rem;">
                
                <?php if (!empty($memory['cover_image'])): ?>
                    <!-- Main Cover Polaroid -->
                    <div class="card random-rotate" style="background-color: white; padding: 1rem 1rem 3rem 1rem; border-radius: 4px; border: var(--thick-border); box-shadow: 8px 8px 0px rgba(0,0,0,0.15); width: 90%; margin: 0 auto; position: relative; z-index: 2;">
                        <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%) rotate(-5deg); width: 100px; height: 25px; background-color: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0,0,0,0.1); box-shadow: 1px 1px 3px rgba(0,0,0,0.1); z-index: 3;"></div>
                        
                        <div style="width: 100%; aspect-ratio: 1/1; background-color: #f1f5f9; border: 2px solid var(--border-color); overflow: hidden;">
                            <img src="<?php echo e($memory['cover_image']); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Cover">
                        </div>
                        <p style="font-family: var(--font-hand); font-size: 1.8rem; text-align: center; margin-top: 1rem; color: var(--fg-main);"><?php echo date('Y', strtotime($memory['event_date'])); ?></p>
                    </div>
                <?php else: ?>
                    <div class="card random-rotate" style="background-color: #f1f5f9; padding: 3rem; text-align: center; border-radius: 4px; border: var(--thick-border); width: 90%; margin: 0 auto;">
                        <span style="font-size: 4rem;">📷</span>
                        <p style="font-family: var(--font-hand); font-size: 1.8rem; margin-top: 1rem;">No photos attached!</p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($photos)): ?>
                    <div style="margin-top: -30px; display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">
                        <?php foreach($photos as $idx => $p): ?>
                            <div class="card random-rotate" style="background-color: white; padding: 0.5rem; border-radius: 2px; border: 2px solid var(--border-color); width: 120px; height: 120px; position: relative; z-index: <?php echo ($idx%2==0) ? 1 : 3; ?>; box-shadow: 4px 4px 0px rgba(0,0,0,0.1);">
                                <img src="<?php echo e($p); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
</div>

<!-- Responsive override for scrapbook -->
<style>
@media (max-width: 800px) {
    .container > .card > div {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
