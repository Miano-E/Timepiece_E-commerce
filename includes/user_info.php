<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$firstLogin = isset($_SESSION['first_login']) ? $_SESSION['first_login'] : false;

// Clear the first login flag after it's used
if ($firstLogin) {
    unset($_SESSION['first_login']);
}
?>
<div class="user-info" id="user-info" style="<?php echo $firstLogin ? '' : 'display:none;'; ?>">
    <?php if ($username && $firstLogin): ?>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
    <?php endif; ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const userInfo = document.getElementById('user-info');
    if (userInfo && userInfo.style.display !== 'none') {
        setTimeout(() => {
            userInfo.style.transition = 'opacity 1s, background-color 1s';
            userInfo.style.opacity = '0';
            userInfo.style.backgroundColor = 'transparent';
            setTimeout(() => {
                userInfo.style.display = 'none';
            }, 1000);
        }, 1500);
    }
});

</script>