<?php
$page_title = "Drawer Contents";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$category = $_GET['category'] ?? 'General';
$user_id = $_SESSION['user_id'];

// Fetch memories for this category
$memories = [];
$stmt = $conn->prepare("SELECT * FROM memories WHERE user_id = ? AND category = ? ORDER BY event_date DESC");
$stmt->bind_param("is", $user_id, $category);
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()) {
    $memories[] = $row;
}
$stmt->close();
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0; text-align: left;">
                <?php echo e($category); ?> Drawer
            </h1>
            <p style="color: #64748b; font-weight: 600; margin-top: 0.5rem;">
                <?php echo count($memories); ?> memories stored here.
            </p>
        </div>
        <div>
            <a href="drawers.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> All Drawers</a>
            <a href="memory-editor.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
        </div>
    </div>

    <?php if (empty($memories)): ?>
        <div class="card" style="text-align: center; padding: 4rem 2rem; border-style: dashed; border-width: 4px; background-color: transparent; box-shadow: none;">
            <div style="font-size: 4rem; margin-bottom: 1rem; color: #cbd5e1;">🗃️</div>
            <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #64748b;">This drawer is empty</h3>
            <p style="margin-bottom: 2rem; color: #94a3b8; font-weight: 500;">Fill it up with your favorite moments.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($memories as $mem): ?>
                <div class="card random-rotate" style="cursor: pointer; padding: 1.5rem;" onclick="location.href='memory-view.php?id=<?php echo $mem['id']; ?>'">
                    <div style="width: 100%; height: 200px; background-color: #f1f5f9; border-radius: 12px; border: 2px solid var(--border-color); margin-bottom: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
                        <?php if (!empty($mem['cover_image'])): ?>
                            <img src="<?php echo e($mem['cover_image']); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Cover">
                        <?php else: ?>
                            <span style="font-size: 3rem;">📸</span>
                        <?php endif; ?>
                        
                        <span class="badge" style="position: absolute; bottom: 10px; right: 10px; background-color: var(--tertiary); color: var(--fg-main); border-color: var(--fg-main);">
                            <?php echo e($mem['mood']); ?>
                        </span>
                    </div>
                    
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?php echo e($mem['title']); ?>
                    </h3>
                    
                    <p style="color: #64748b; font-size: 0.95rem; font-weight: 500; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($mem['event_date'])); ?></span>
                        <span style="opacity: 0.5;">
                            <?php if($mem['privacy'] == 'private') echo '<i class="fas fa-lock"></i>'; ?>
                            <?php if($mem['privacy'] == 'public') echo '<i class="fas fa-globe"></i>'; ?>
                        </span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
