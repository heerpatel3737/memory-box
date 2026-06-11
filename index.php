<?php
$page_title = "Home";
include_once __DIR__ . '/includes/header.php';
?>

<div class="blob blob-primary"></div>
<div class="blob blob-secondary"></div>

<div class="container" style="text-align: center; padding-top: 5rem; padding-bottom: 5rem; position: relative;">
    <h1 class="page-title" style="font-size: 4rem; margin-bottom: 1.5rem;">
        Preserve Your <span style="color: var(--primary);">Memories</span>.<br>
        Relive Your <span style="color: var(--secondary);">Stories</span>.
    </h1>
    
    <p class="page-subtitle" style="font-size: 1.3rem; max-width: 600px; margin: 0 auto 3rem auto;">
        Memory Box is your digital scrapbook and personal time capsule. 
        Safely store childhood memories, write letters to your future self, and visually explore the timeline of your life.
    </p>

    <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
        <?php if (is_logged_in()): ?>
            <a href="dashboard.php" class="btn btn-primary" style="font-size: 1.25rem; padding: 1rem 2.5rem;">
                <i class="fas fa-rocket"></i> Go to Dashboard
            </a>
        <?php else: ?>
            <a href="signup.php" class="btn btn-primary" style="font-size: 1.25rem; padding: 1rem 2.5rem;">
                <i class="fas fa-magic"></i> Start Preserving Free
            </a>
            <a href="login.php" class="btn btn-tertiary" style="font-size: 1.25rem; padding: 1rem 2.5rem;">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem;">Everything you need to save what matters</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        
        <!-- Feature 1 -->
        <div class="card" style="border-top: 10px solid var(--primary);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🗂️</div>
            <h3 class="card-title">Interactive Drawers</h3>
            <p style="color: #64748b; line-height: 1.6;">
                Organize your life into beautiful digital drawers. Sort by Childhood, School Days, Friends, and Family with colorful, sticker-like categories.
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="card" style="border-top: 10px solid var(--secondary);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">💌</div>
            <h3 class="card-title">Future Letters Vault</h3>
            <p style="color: #64748b; line-height: 1.6;">
                Write a letter to your future self or a loved one. The envelope remains completely locked with a countdown timer until the delivery date arrives!
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="card" style="border-top: 10px solid var(--tertiary);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📸</div>
            <h3 class="card-title">Digital Scrapbook</h3>
            <p style="color: #64748b; line-height: 1.6;">
                View your memories as a real scrapbook. We lay out your uploaded photos like polaroids with washi tape and sticky notes.
            </p>
        </div>

    </div>
</div>

<div class="container" style="margin-top: 4rem; margin-bottom: 5rem; text-align: center;">
    <div class="card" style="background-color: var(--quaternary); display: inline-block; padding: 3rem; max-width: 800px; box-shadow: var(--double-shadow);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: white; -webkit-text-stroke: 2px var(--fg-main);">Ready to build your timeline?</h2>
        <p style="font-size: 1.2rem; font-weight: 600; margin-bottom: 2rem;">Join thousands of others keeping their memories safe.</p>
        <a href="signup.php" class="btn btn-secondary" style="font-size: 1.3rem; padding: 1rem 3rem;">
            Create Your Box 🎁
        </a>
    </div>
</div>

<?php
include_once __DIR__ . '/includes/footer.php';
?>
