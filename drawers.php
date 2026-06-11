<?php
$page_title = "Drawers";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$user_id = $_SESSION['user_id'];

// Get counts for each category
$category_counts = [];
$res = $conn->query("SELECT category, COUNT(*) as cnt FROM memories WHERE user_id = $user_id GROUP BY category");
while ($row = $res->fetch_assoc()) {
    $category_counts[$row['category']] = $row['cnt'];
}

function getCount($cat, $counts) {
    return isset($counts[$cat]) ? $counts[$cat] : 0;
}

$drawers = [
    [
        'name' => 'Childhood',
        'icon' => '🧸',
        'color' => 'var(--tertiary)',
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'Family',
        'icon' => '👨‍👩‍👧',
        'color' => 'var(--secondary)',
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'Friends',
        'icon' => '👫',
        'color' => 'var(--primary)',
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'School Days',
        'icon' => '📚',
        'color' => '#3b82f6', // Blue
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'Dreams',
        'icon' => '🌠',
        'color' => '#8b5cf6', // Purple variant
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'Letters',
        'icon' => '💌',
        'color' => 'var(--quaternary)',
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'Travel',
        'icon' => '✈️',
        'color' => '#f97316', // Orange
        'shadow' => 'var(--fg-main)'
    ],
    [
        'name' => 'Achievements',
        'icon' => '🏆',
        'color' => '#eab308', // Yellow
        'shadow' => 'var(--fg-main)'
    ]
];
?>

<div class="container" style="padding-top: 4rem; padding-bottom: 5rem;">
    <div style="text-align: center; margin-bottom: 4rem;">
        <h1 class="page-title">Memory Drawers 🎁</h1>
        <p class="page-subtitle">Open a drawer to explore or add new memories to a specific chapter of your life.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2.5rem;">
        <?php foreach ($drawers as $index => $drawer): ?>
            <?php 
                $count = getCount($drawer['name'], $category_counts);
                // Rotate cards slightly in alternating directions for the scrapbook feel
                $rotation = ($index % 2 == 0) ? '-1.5deg' : '1.5deg'; 
            ?>
            <a href="memories.php?category=<?php echo urlencode($drawer['name']); ?>" style="display: block;">
                <div class="card random-rotate" style="border-top: 15px solid <?php echo $drawer['color']; ?>; text-align: center; padding: 3rem 2rem; transform: rotate(<?php echo $rotation; ?>);">
                    <div style="font-size: 5rem; margin-bottom: 1.5rem; text-shadow: 4px 4px 0px rgba(0,0,0,0.1); display: inline-block; transition: transform 0.3s ease;" class="drawer-icon">
                        <?php echo $drawer['icon']; ?>
                    </div>
                    <h2 style="font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $drawer['name']; ?></h2>
                    <span class="badge" style="background-color: var(--bg-page); color: var(--fg-main);">
                        <?php echo $count; ?> Memories
                    </span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* Add hover effects for drawer icons */
.card:hover .drawer-icon {
    transform: scale(1.15) rotate(-10deg);
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
