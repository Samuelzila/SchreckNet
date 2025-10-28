<?php
require_once "../src/auth.php";
require_once "../src/posts.php";
requireLogin();
$user = currentUser();
$posts = getAllPosts();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><?php echo SITE_TITLE; ?></title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
	<p><a href="admin.php">[Admin Panel]</a> <a href="index.php">[Logout]</a></p>
	<hr>
	<?php foreach ($posts as $post): ?>
		<div class="post">
			<strong><?php echo htmlspecialchars($post['title']); ?></strong>
			<small>by <?php echo htmlspecialchars($post['author']); ?> on <?php echo htmlspecialchars($post['created_at']); ?></small>
			<p><?php echo nl2br(htmlspecialchars($post["body"])); ?></p>
		</div>
		<hr>
	<?php endforeach; ?>
</body>

</html>
