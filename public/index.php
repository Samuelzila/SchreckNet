<?php
require_once "../src/auth.php";
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
	<title> SchreckNET Login </title>
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
</body>

</html>
