<?php
require_once "../src/auth.php";
require_once "../src/posts.php";
requireLogin();
$user = currentUser();
if ($user['role'] !== 'admin') {
	die("Access denied.");
}

// Handle new post
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
	createPost($_POST["author_id"], $_POST["title"], $_POST["body"]);
	header("Location: admin.php");
	exit();
}

// Handle impersonation
if (isset($_GET['impersonate'])) {
	impersonate($_GET['impersonate']);
	header("Location: posts.php");
	exit();
}

$db = getDB();
$users = $db->query("SELECT id, username FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title> SchreckNET Admin Panel </title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<h1> Storyteller Control</h1>
	<h2>Create Post</h2>
	<form method="POST" action="admin.php">
		<label>Author:</label>
		<select name="author_id">
			<?php foreach ($users as $u): ?>
				<option value="<?php echo htmlspecialchars($u['id']); ?>"><?php echo htmlspecialchars($u['username']); ?></option>
			<?php endforeach; ?>

		</select><br>
		<label>Title:</label>
		<input type="text" name="title" required><br>
		<label>Body:</label>
		<textarea name="body" rows="5" cols="60"></textarea><br>
		<button type="submit">Post</button>
	</form>

	<h2>Impersonate</h2>
	<ul>
		<?php foreach ($users as $u): ?>
			<li>
				<a href="?impersonate=<?= $u['id']; ?>">Login as <?= htmlspecialchars($u['username']); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</body>

</html>
