<?php
require_once '../src/auth.php';
require_once '../src/db.php';
require_once '../src/forum.php';
requireLogin();
// This page allows editing a post via a form submission.
if (!isStoryteller()) {
	http_response_code(403);
	echo "Forbidden";
	exit;
}
$postId = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare("SELECT body FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
	http_response_code(404);
	echo "Post not found";
	exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newBody = $_POST['body'];
	$stmt = $db->prepare("UPDATE posts SET body = ? WHERE id = ?");
	$stmt->execute([$newBody, $postId]);
	header('Location: thread.php?id=' . getThreadIdFromPostId($postId));
	exit;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Post</title>
</head>

<body>
	<h1>Edit Post</h1>
	<form method="POST" action="">
		<textarea name="body" rows="10" cols="80"><?php echo htmlspecialchars($post['body']); ?></textarea><br>
		<button type="submit">Save Changes</button>
	</form>
	<a href="thread.php?id=<?= getThreadIdFromPostId($postId) ?>">Cancel</a>
</body>

</html>
