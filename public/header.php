<?php require_once "../config/config.php"; ?>
<div class="header">
	<h1><?= SITE_TITLE ?></h1>
	<nav>
		<a href="login.php">Home</a>
		<?php if (isLoggedIn()): ?>
			<a href="profile.php">Profile</a>
			<?php if (isAdmin()): ?>
				<a href="admin.php">Admin Panel</a>
			<?php endif; ?>
			<?php if (isStoryteller()): ?>
				<a href="storyteller.php">Storyteller Dashboard</a>
			<?php endif; ?>
			<a href="logout.php">Logout</a>
		<?php else: ?>
			<a href="login.php">Login</a>
			<a href="register.php">Register</a>
		<?php endif; ?>
	</nav>
</div>
