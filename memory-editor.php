<?php
$page_title = "Add Memory";
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/db.php';
require_auth();

$message = "";
$is_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? 'General';
    $event_date = $_POST['event_date'] ?? date('Y-m-d');
    $mood = $_POST['mood'] ?? '😊 Happy';
    $privacy = $_POST['privacy'] ?? 'private';
    
    if (empty($title) || empty($description)) {
        $message = "Title and Description are required.";
        $is_error = true;
    } else {
        // Handle optional cover image upload
        $cover_image = null;
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $target_dir = __DIR__ . "/assets/uploads/memories/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                
                $new_filename = uniqid('cover_', true) . '.' . $ext;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_dir . $new_filename)) {
                    $cover_image = "assets/uploads/memories/" . $new_filename;
                }
            }
        }

        // Insert memory into DB
        $stmt = $conn->prepare("INSERT INTO memories (user_id, title, description, category, event_date, mood, privacy, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $user_id, $title, $description, $category, $event_date, $mood, $privacy, $cover_image);
        
        if ($stmt->execute()) {
            $memory_id = $conn->insert_id;
            
            // Handle multiple photos
            if (isset($_FILES['photos'])) {
                upload_photos($_FILES['photos'], $memory_id);
            }
            
            // Handle tags if any
            $tags = trim($_POST['tags'] ?? '');
            if (!empty($tags)) {
                $tag_array = explode(',', $tags);
                $stmt_tag = $conn->prepare("INSERT IGNORE INTO memory_tags (memory_id, tag_name) VALUES (?, ?)");
                foreach ($tag_array as $tag) {
                    $clean_tag = trim($tag);
                    if (!empty($clean_tag)) {
                        $stmt_tag->bind_param("is", $memory_id, $clean_tag);
                        $stmt_tag->execute();
                    }
                }
                $stmt_tag->close();
            }
            
            header("Location: memory-view.php?id=" . $memory_id);
            exit();
        } else {
            $message = "Error saving memory. Please try again.";
            $is_error = true;
        }
        $stmt->close();
    }
}
?>

<div class="container" style="max-width: 800px; padding-top: 3rem; padding-bottom: 5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <h1 class="page-title" style="margin-bottom: 0;">Add New Memory ✍️</h1>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div class="card" style="border-top: 10px solid var(--primary);">
        <?php if ($message): ?>
            <div style="background-color: <?php echo $is_error ? '#fef2f2' : '#f0fdf4'; ?>; border: var(--thick-border); border-color: <?php echo $is_error ? '#ef4444' : '#22c55e'; ?>; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600;">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <form action="memory-editor.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label" for="title">Title of your memory</label>
                <input type="text" id="title" name="title" class="form-control" required placeholder="My First Bike Ride..." style="font-size: 1.5rem; font-family: var(--font-heading); font-weight: 700;">
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="category">Category Drawer</label>
                    <select id="category" name="category" class="form-control">
                        <option value="Childhood">🧸 Childhood</option>
                        <option value="Family">👨‍👩‍👧 Family</option>
                        <option value="Friends">👫 Friends</option>
                        <option value="School Days">📚 School Days</option>
                        <option value="Dreams">🌠 Dreams</option>
                        <option value="Letters">💌 Letters</option>
                        <option value="Travel">✈️ Travel</option>
                        <option value="Achievements">🏆 Achievements</option>
                        <option value="General">📂 General</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="event_date">Date of Memory</label>
                    <input type="date" id="event_date" name="event_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">How did you feel? (Mood)</label>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;" id="mood-selector">
                    <?php 
                    $moods = ['😊 Happy', '🥺 Nostalgic', '😢 Sad', '🤩 Excited', '😴 Dreamy', '😎 Adventurous'];
                    foreach($moods as $idx => $m): 
                    ?>
                        <label style="cursor: pointer;">
                            <input type="radio" name="mood" value="<?php echo $m; ?>" style="display: none;" <?php echo $idx === 0 ? 'checked' : ''; ?>>
                            <span class="badge mood-badge" style="padding: 0.5rem 1rem; font-size: 1.1rem; background-color: var(--card-white); transition: all 0.2s;">
                                <?php echo $m; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">The Story</label>
                <textarea id="description" name="description" class="form-control" rows="8" required placeholder="Write all the beautiful details here..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="tags">Tags (comma separated)</label>
                <input type="text" id="tags" name="tags" class="form-control" placeholder="e.g. summer, beach, birthday">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="cover_image">Cover Photo</label>
                    <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*" style="padding: 0.6rem;">
                </div>
                <div class="form-group">
                    <label class="form-label" for="photos">Extra Photos (Scrapbook)</label>
                    <input type="file" id="photos" name="photos[]" class="form-control" accept="image/*" multiple style="padding: 0.6rem;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="privacy">Privacy</label>
                <select id="privacy" name="privacy" class="form-control" style="width: 200px;">
                    <option value="private">🔒 Private (Only Me)</option>
                    <option value="friends">👥 Friends Only</option>
                    <option value="public">🌐 Public</option>
                </select>
            </div>

            <hr style="border: 1px dashed var(--border-color); margin: 2rem 0;">

            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.25rem; padding: 1.25rem;">
                <i class="fas fa-save"></i> Save to Memory Box
            </button>
        </form>
    </div>
</div>

<script>
// Script to handle custom mood badge styling when selected
document.addEventListener('DOMContentLoaded', () => {
    const moodRadios = document.querySelectorAll('input[name="mood"]');
    
    function updateMoods() {
        moodRadios.forEach(radio => {
            const span = radio.nextElementSibling;
            if (radio.checked) {
                span.style.backgroundColor = 'var(--tertiary)';
                span.style.transform = 'translateY(-2px)';
                span.style.boxShadow = '4px 4px 0px var(--border-color)';
            } else {
                span.style.backgroundColor = 'var(--card-white)';
                span.style.transform = 'none';
                span.style.boxShadow = '2px 2px 0px var(--border-color)';
            }
        });
    }

    moodRadios.forEach(radio => {
        radio.addEventListener('change', updateMoods);
    });
    
    updateMoods(); // Init
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
