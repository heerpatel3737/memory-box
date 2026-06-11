<?php
$page_title = "Secret Confessions";
include_once __DIR__ . '/includes/header.php';
require_auth();
?>

<div class="container" style="max-width: 700px; padding-top: 4rem; padding-bottom: 5rem;">
    
    <!-- Lock Screen Wrapper -->
    <div id="lockScreen" class="card" style="border-top: 10px solid var(--fg-main); text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 5rem; margin-bottom: 1rem; color: var(--fg-main);">🤐</div>
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Confessions Box</h1>
        <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 2rem;">Enter your 4-digit secret PIN to unlock your private rants and secrets.</p>
        
        <div style="max-width: 300px; margin: 0 auto;">
            <input type="password" id="pinInput" class="form-control" maxlength="4" placeholder="••••" style="font-size: 2rem; text-align: center; letter-spacing: 1rem; padding: 1rem; font-family: monospace; border-width: 4px; margin-bottom: 1.5rem;">
            
            <button onclick="checkPin()" class="btn btn-primary" style="width: 100%; font-size: 1.3rem; padding: 1rem;">
                <i class="fas fa-unlock-alt"></i> Unlock Box
            </button>
            <p id="pinError" style="color: #ef4444; font-weight: 700; margin-top: 1rem; display: none;">Incorrect PIN! (Hint: try 1234)</p>
        </div>
    </div>

    <!-- Confessions Content (Hidden by default) -->
    <div id="confessionsContent" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 class="page-title" style="margin-bottom: 0;">🤫 Secret Rants</h1>
            <button onclick="lockBox()" class="btn btn-secondary" style="padding: 0.5rem 1.5rem;"><i class="fas fa-lock"></i> Lock Now</button>
        </div>

        <div class="card" style="background-color: var(--fg-main); color: white; margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem;">
                <textarea id="newRant" class="form-control" rows="3" placeholder="Get it off your chest..." style="flex: 1; border-color: #334155; background-color: #0f172a; color: white;"></textarea>
                <button onclick="addRant()" class="btn btn-tertiary" style="align-self: flex-end;"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>

        <div id="rantList" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Mock Rants -->
            <div class="card random-rotate" style="background-color: #f87171; color: white; border-color: var(--fg-main); transform: rotate(-1deg);">
                <span style="font-size: 2rem; position: absolute; right: 10px; top: 10px; opacity: 0.5;">😤</span>
                <p style="font-family: var(--font-hand); font-size: 1.8rem; line-height: 1.4;">"She left me on read for 3 hours and then posted a story at a cafe! The audacity!"</p>
                <small style="opacity: 0.8; font-weight: 600;">- 2 days ago</small>
            </div>

            <div class="card random-rotate" style="background-color: #60a5fa; color: white; border-color: var(--fg-main); transform: rotate(1.5deg);">
                <span style="font-size: 2rem; position: absolute; right: 10px; top: 10px; opacity: 0.5;">🙄</span>
                <p style="font-family: var(--font-hand); font-size: 1.8rem; line-height: 1.4;">"My boss CC'd the entire team just to correct a single typo in my email. Wow."</p>
                <small style="opacity: 0.8; font-weight: 600;">- Last week</small>
            </div>
        </div>
    </div>

</div>

<script>
// Mock PIN interaction
function checkPin() {
    const pin = document.getElementById('pinInput').value;
    const error = document.getElementById('pinError');
    // For demo purposes, the PIN is 1234
    if(pin === '1234') {
        document.getElementById('lockScreen').style.display = 'none';
        document.getElementById('confessionsContent').style.display = 'block';
        error.style.display = 'none';
        document.getElementById('pinInput').value = '';
    } else {
        error.style.display = 'block';
        // Shake effect
        const lockBox = document.getElementById('lockScreen');
        lockBox.style.transform = 'translateX(-10px)';
        setTimeout(() => lockBox.style.transform = 'translateX(10px)', 100);
        setTimeout(() => lockBox.style.transform = 'translateX(-10px)', 200);
        setTimeout(() => lockBox.style.transform = 'translateX(0)', 300);
    }
}

function lockBox() {
    document.getElementById('lockScreen').style.display = 'block';
    document.getElementById('confessionsContent').style.display = 'none';
}

function addRant() {
    const input = document.getElementById('newRant');
    const list = document.getElementById('rantList');
    if(input.value.trim() !== '') {
        const div = document.createElement('div');
        div.className = 'card random-rotate';
        div.style.backgroundColor = 'var(--quaternary)';
        div.style.color = 'white';
        div.style.borderColor = 'var(--fg-main)';
        div.style.transform = `rotate(${(Math.random() * 4 - 2).toFixed(1)}deg)`;
        
        div.innerHTML = `
            <span style="font-size: 2rem; position: absolute; right: 10px; top: 10px; opacity: 0.5;">🔥</span>
            <p style="font-family: var(--font-hand); font-size: 1.8rem; line-height: 1.4;">"${input.value}"</p>
            <small style="opacity: 0.8; font-weight: 600;">- Just now</small>
        `;
        
        list.insertBefore(div, list.firstChild);
        input.value = '';
    }
}
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
