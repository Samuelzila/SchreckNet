<?php
require_once "../src/db.php";
require_once "../src/auth.php";
require_once "../src/forum.php";
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['name'] ?? '');
	$description = trim($_POST['description'] ?? '');
	if ($name) {
		createBoard($name, $description ?: null);
		header('Location: ./forum.php');
		exit;
	} else {
		$error = "Board name cannot be empty.";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Create New Board</title>
	<link rel="stylesheet" href="assets/css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<h1>Create New Board</h1>
	<?php if (!empty($error)): ?>
		<p class="error"><?= htmlspecialchars($error) ?></p>
	<?php endif; ?>
	<form method="POST" action="new_board.php">
		<label for="name">Board Name:</label><br>
		<input type="text" id="name" name="name" required><br><br>
		<label for="description">Description (optional):</label><br>
		<textarea id="description" name="description"></textarea><br><br>
		<input type="submit" value="Create Board">
	</form>
	<p><a href="forum.php">Back to Forum</a></p>
</body>

</html>
