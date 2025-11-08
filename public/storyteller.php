<?php
require_once "../src/auth.php";
requireLogin();
$user = currentUser();
if (!isAdmin()) {
	die("Access denied.");
}

// Handle character creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$clan = $_POST['clan'] ?? '';
	$affiliation = $_POST['affiliation'] ?? '';

	$password = bin2hex(random_bytes(4)); // Generate a random 8-character password
	createUser($username, $password, 'SPC', $clan, $affiliation);
	echo "<p>Character '$username' created with password: <strong>$password</strong></p>";
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
	<?php include 'header.php'; ?>
	<h1> Storyteller Control</h1>
	<h2>Create SPC</h2>
	<form method="POST">
		<label for="username">Username:</label>
		<br>
		<input type="text" id="username" name="username" required>
		<br>
		<label for="clan">Clan:</label>
		<br>
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
		</select>
		<br>
		<label for="affiliation"> Affiliation:</label>
		<br>
		<input type="text" name="affiliation" id="affiliation" placeholder="Paris Camarilla, Digital Draculas, Los Angeles Anarchs...">
		<br>
		<input type="submit" value="Create Character">

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
