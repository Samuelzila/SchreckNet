<?php
require_once "../src/auth.php";
require_once "../src/forum.php";
require_once "../config/config.php";

requireLogin();
$boards = getBoards();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><?php echo SITE_TITLE ?></title>
	<link rel="stylesheet" href="assets/css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<?php include 'header.php'; ?>
	<ul>
		<?php foreach ($boards as $b): ?>
			<li><a href="board.php?id=<?= $b["id"] ?>">
					<strong><?= htmlspecialchars($b["name"]); ?></strong> - <?= htmlspecialchars($b["description"]); ?>
				</a></li>
		<?php endforeach; ?>
	</ul>
	<?php
	// If the user is an admin, add an option to create a board
	if (isAdmin()) {
		echo '<a href="new_board.php">Create New Board</a>';
	}
	?>
</body>

</html>
