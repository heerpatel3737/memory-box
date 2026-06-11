<?php
$page_title = "My Timeline";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$user_id = $_SESSION['user_id'];

// Fetch all memories ordered by date
$memories = [];
$stmt = $conn->prepare("SELECT * FROM memories WHERE user_id = ? ORDER BY event_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

// Group memories by Year
$grouped = [];
while($row = $res->fetch_assoc()) {
    $year = date('Y', strtotime($row['event_date']));
    if (!isset($grouped[$year])) {
        $grouped[$year] = [];
    }
    $grouped[$year][] = $row;
}
$stmt->close();
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    <div style="text-align: center; margin-bottom: 4rem;">
        <h1 class="page-title">Life Timeline 🕰️</h1>
        <p class="page-subtitle">Scroll through the chapters of your journey.</p>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="card" style="text-align: center; padding: 4rem; max-width: 600px; margin: 0 auto; border-style: dashed;">
            <div style="font-size: 3rem;">🌱</div>
            <h2 style="margin-top: 1rem;">Your timeline is a blank canvas.</h2>
            <p style="color: #64748b; margin-top: 0.5rem; margin-bottom: 1.5rem;">Start by adding memories to build your life's story.</p>
            <a href="memory-editor.php" class="btn btn-primary">Start Preserving</a>
        </div>
    <?php else: ?>
        <div style="position: relative; max-width: 800px; margin: 0 auto;">
            <!-- Vertical Line -->
            <div style="position: absolute; left: 50%; transform: translateX(-50%); top: 0; bottom: 0; width: 6px; background-color: var(--border-color); border-radius: 3px; z-index: 1;"></div>

            <?php foreach ($grouped as $year => $mems): ?>
                
                <!-- Year Marker -->
                <div style="display: flex; justify-content: center; margin: 3rem 0; position: relative; z-index: 2;">
                    <div class="badge" style="background-color: var(--quaternary); color: white; font-size: 1.5rem; padding: 0.5rem 2rem; border-radius: 999px; border: var(--thick-border); box-shadow: var(--hard-shadow);">
                        <?php echo $year; ?>
                    </div>
                </div>

                <!-- Memories for the year -->
                <?php foreach ($mems as $idx => $mem): ?>
                    <?php 
                        $is_left = ($idx % 2 === 0);
                        $flex_dir = $is_left ? 'row' : 'row-reverse';
                        $align = $is_left ? 'flex-end' : 'flex-start';
                        $text_align = $is_left ? 'right' : 'left';
                    ?>
                    
                    <div style="display: flex; flex-direction: <?php echo $flex_dir; ?>; align-items: center; justify-content: space-between; width: 100%; margin-bottom: 2rem; position: relative; z-index: 2;">
                        
                        <!-- Empty Spacer -->
                        <div style="width: 45%;"></div>
                        
                        <!-- Dot on timeline -->
                        <div style="width: 20px; height: 20px; background-color: var(--secondary); border: 4px solid var(--border-color); border-radius: 50%; z-index: 3;"></div>
                        
                        <!-- Card Content -->
                        <div style="width: 45%; display: flex; justify-content: <?php echo $align; ?>;">
                            <div class="card random-rotate" style="padding: 1.5rem; cursor: pointer; text-align: <?php echo $text_align; ?>; border-top: 8px solid var(--primary); width: 100%;" onclick="location.href='memory-view.php?id=<?php echo $mem['id']; ?>'">
                                <span style="font-weight: 700; color: var(--primary); font-size: 0.9rem;"><?php echo date('M d', strtotime($mem['event_date'])); ?></span>
                                <h3 style="font-size: 1.3rem; margin: 0.5rem 0;"><?php echo e($mem['title']); ?></h3>
                                <p style="color: #64748b; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo e($mem['description']); ?>
                                </p>
                                <div style="margin-top: 1rem;">
                                    <span class="badge" style="background-color: var(--bg-page); font-size: 0.8rem;"><?php echo e($mem['category']); ?></span>
                                    <span class="badge" style="background-color: var(--bg-page); font-size: 0.8rem;"><?php echo e($mem['mood']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
@media (max-width: 600px) {
    .container > div > div:first-child {
        left: 20px !important;
        transform: none !important;
    }
    .container > div > div[style*="justify-content: center;"] {
        justify-content: flex-start !important;
        margin-left: 0 !important;
    }
    .container > div > div[style*="display: flex; flex-direction:"] {
        flex-direction: row-reverse !important;
    }
    .container > div > div[style*="display: flex; flex-direction:"] > div:first-child {
        display: none;
    }
    .container > div > div[style*="display: flex; flex-direction:"] > div:nth-child(2) {
        margin-left: 10px;
    }
    .container > div > div[style*="display: flex; flex-direction:"] > div:last-child {
        width: calc(100% - 40px) !important;
    }
    .card.random-rotate {
        text-align: left !important;
    }
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
