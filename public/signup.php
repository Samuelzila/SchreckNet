<?php

require_once "../src/auth.php";
require_once "../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$clan = $_POST['clan'] ?? '';
	$affiliation = $_POST['affiliation'] ?? '';

	createUser($username, $password, 'player', $clan, $affiliation);
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title> <?php echo SITE_TITLE ?> Sign Up </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<h1> Sign Up for <?php echo SITE_TITLE ?> </h1>
	<form method="POST">
		<label> Username:</label><br>
		<input type="text" name="username" required><br>
		<label> Password:</label><br>
		<input type="password" name="password" required><br>
		<label for="clan"> Clan:</label><br>
		<select name="clan" id="clan">
			<option value=""> Select your clan </option>
			<option value="Banu Haqim"> Banu Haqim </option>
			<option value="Brujah"> Brujah </option>
			<option value="Gangrel"> Gangrel </option>
			<option value="Giovanni"> Giovanni </option>
			<option value="Lasombra"> Lasombra </option>
			<option value="Malkavian"> Malkavian </option>
			<option value="Ministry"> Ministry </option>
			<option value="Nosferatu"> Nosferatu </option>
			<option value="Ravnos"> Ravnos </option>
			<option value="Toreador"> Toreador </option>
			<option value="Tremere"> Tremere </option>
			<option value="Tzimisce"> Tzimisce </option>
			<option value="Ventrue"> Ventrue </option>
			<option value="Other"> Other </option>
		</select><br>
		<label for="affiliation"> Affiliation:</label><br>
		<input type="text" name="affiliation" id="affiliation" placeholder="Paris Camarilla, Digital Draculas, Los Angeles Anarchs..."><br>
		<button type="submit"> Create Account </button>
	</form>
	<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
	<a href="login.php"> Back to Login </a>
</body>

</html>
