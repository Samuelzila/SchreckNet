<?php
require_once "../src/db.php";
require_once "../src/auth.php";
require_once "../src/forum.php";
requireLogin();

$boardId = $_GET['board'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = trim($_POST['title'] ?? '');
	$body = trim($_POST['body'] ?? '');
	$author = currentUser();
	if ($title && $boardId) {
		$threadId = createThread($boardId, $author["id"], $title, $body ?: null);
		if (!empty($_FILES['attachment']['name'])) {
			$postId = getPosts($threadId)[0]['id'];
			addAttachment($postId, $_FILES['attachment']);
		}
		header("Location: ./board.php?id=" . urlencode($boardId));
		exit;
	} else {
		$error = "Thread title cannot be empty.";
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Create New Thread</title>
	<link rel="stylesheet" href="assets/css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<h1>Create New Thread</h1>
	<?php if (!empty($error)): ?>
		<p class="error"><?=
					htmlspecialchars($error) ?></p>
	<?php endif; ?>
	<form method="POST" enctype="multipart/form-data">
		<label for="title">Title:</label><br>
		<input type="text" id="title" name="title" required><br><br>
		<label for="body">Body</label><br>
		<textarea id="content" name="body"></textarea><br><br>
		<label for="attachment">Attach File:</label>
		<input type="file" id="attachment" name="attachment"><br><br>
		<input type="submit" value="Create Thread">
	</form>
	<p><a href="board.php?id=<?= htmlspecialchars(urlencode($boardId)) ?>">Back to Board</a></p>
</body>
