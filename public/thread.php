<?php
require_once '../src/auth.php';
require_once '../src/forum.php';
require_once '../src/bbcode.php';
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
	<?php include 'header.php'; ?>
	<h1><?= htmlspecialchars($posts[0]['title'] ?? 'Thread'); ?></h1>
	<?php foreach ($posts as $p): ?>
		<div class="post">
			<strong><?= htmlspecialchars($p['username']); ?></strong> (<?= $p['created_at']; ?>)
			<div class="avatar-container">
				<img class="avatar" src="<?= htmlspecialchars($p['avatar']); ?>" alt="Profile Picture">
			</div>
			<p><?= parseBBCode($p["body"]) ?></p>
			<?php
			$attachments = getAttachments($p['id']);
			foreach ($attachments as $f) {
				if ($f["type"] == "image"):
			?>
					<img src="<?= $f["stored_as"] ?>" class="post-image" alt="Attachment"><br>
				<?php else: ?>
					<a href="<?= $f["stored_as"] ?>" download><?= $f["original_name"] ?></a><br>
				<?php endif; ?>
			<?php } ?>
		</div>
		<hr>
	<?php endforeach; ?>
	<form method="POST" action="reply.php" enctype="multipart/form-data">
		<input type="hidden" name="thread_id" value="<?= $threadId; ?>">
		<textarea name="body" rows="4" cols="60"></textarea><br>
		Attach File: <input type="file" name="attachment"><br>
		<button type="submit">Reply</button>
	</form>
	<a href="threads.php?board=<?= $posts[0]['board_id'] ?? 1 ?>">Back</a>
</body>

</html>
