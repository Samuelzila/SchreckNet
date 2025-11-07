<?php
require_once '../src/auth.php';
require_once '../src/forum.php';
requireLogin();
$threadId = (int)$_GET['id'];
$posts = getPosts($threadId);
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<h1><?= htmlspecialchars($posts[0]['title'] ?? 'Thread'); ?></h1>
	<?php foreach ($posts as $p): ?>
		<div class="post">
			<strong><?= htmlspecialchars($p['username']); ?></strong> (<?= $p['created_at']; ?>)
			<div class="profile-pic">
				<img src="<?= htmlspecialchars($p['avatar']); ?>" alt="Profile Picture" width="50" height="50">
			</div>
			<p><?= nl2br(htmlspecialchars($p['body'])); ?></p>
		</div>
		<hr>
	<?php endforeach; ?>
	<form method="POST" action="reply.php">
		<input type="hidden" name="thread_id" value="<?= $threadId; ?>">
		<textarea name="body" rows="4" cols="60"></textarea><br>
		<button type="submit">Reply</button>
	</form>
	<a href="threads.php?board=<?= $posts[0]['board_id'] ?? 1 ?>">Back</a>
</body>

</html>
