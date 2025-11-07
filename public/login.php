<?php
require_once "../src/auth.php";
require_once "../config/config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (authenticate($_POST['username'], $_POST['password'])) {
		header('Location: forum.php');
		exit;
	}
	$error = "Access denied. Invalid credentials.";
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title> <?php echo SITE_TITLE ?> Login </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<h1> SchreckNET </h1>
	<form method="POST">
		<label> User:</label><br>
		<input type="text" name="username" required><br>
		<label> Password:</label><br>
		<input type="password" name="password" required><br>
		<button type="submit"> Connect </button>
	</form>
	<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

	<a href="signup.php"> Create an account </a>
</body>

</html>
