<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<div class="user-info" id="user-info">
    <?php if ($username): ?>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
    <?php else: ?>
        <p id="not-logged-in">You are not logged in.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notLoggedInMessage = document.getElementById('not-logged-in');
    const userInfo = document.getElementById('user-info');
    if (notLoggedInMessage) {
        setTimeout(() => {
            notLoggedInMessage.style.transition = 'opacity 1s, background-color 1s';
            notLoggedInMessage.style.opacity = '0';
            userInfo.style.transition = 'background-color 1s';
            userInfo.style.backgroundColor = 'transparent';
            setTimeout(() => {
                notLoggedInMessage.style.display = 'none';
            }, 1000);
        }, 1500);
    }
});
</script>
