    </div>
    <!-- Main Content wrapper ends -->

    <!-- Global Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h3><i class="fas fa-gift"></i> Memory Box</h3>
                <p style="color: #64748b; margin-top: 0.5rem; line-height: 1.6;">
                    A digital space to preserve, organize, and relive the most important moments of your life journey.
                </p>
            </div>
            
            <div class="footer-col">
                <h3><i class="fas fa-phone-alt"></i> Contact Us</h3>
                <dl>
                    <dt><i class="fas fa-mobile-alt"></i> Mobile:</dt>
                    <dd>256464949</dd>
                    <dt><i class="fas fa-envelope"></i> Email:</dt>
                    <dd>memorybox@gmail.com</dd>
                </dl>
            </div>

            <div class="footer-col">
                <h3><i class="fas fa-link"></i> Quick Links</h3>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3><i class="fas fa-share-alt"></i> Connect</h3>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="#" class="btn btn-secondary" style="padding: 0.5rem 1rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-tertiary" style="padding: 0.5rem 1rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-quaternary" style="padding: 0.5rem 1rem;"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>

        <div class="copy">
            &copy; <?php echo date("Y"); ?> Memory Box. All rights reserved.
        </div>
    </footer>

    <!-- Main interactive scripts -->
    <script src="assets/js/main.js"></script>
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js_file): ?>
            <script src="assets/js/<?php echo e($js_file); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
