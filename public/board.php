<?php
require_once "../src/auth.php";
require_once "../src/forum.php";
requireLogin();
$boardId = (int)$_GET["id"];
$threads = getThreads($boardId);
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<h1>Threads</h1>
	<a href="new_thread.php?board=<?php echo $boardId; ?>">[New Thread]</a>
	<ul>
		<?php foreach ($threads as $t): ?>
			<li><a href="thread.php?id=<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['title']); ?></a> by <?php echo htmlspecialchars($t['username']); ?></li>
		<?php endforeach; ?>
	</ul>
	<a href="forum.php">Back to Boards</a>
</body>

</html>
