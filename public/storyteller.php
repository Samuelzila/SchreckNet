<?php
require_once "../src/auth.php";
requireLogin();
$user = currentUser();
if (!isAdmin()) {
	die("Access denied.");
}

// Handle character creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_character') {
	$username = $_POST['username'];
	$clan = $_POST['clan'] ?? '';
	$affiliation = $_POST['affiliation'] ?? '';

	$password = bin2hex(random_bytes(10));
	createUser($username, $password, 'spc', $clan, $affiliation);
	echo "<p>Character '$username' created with password: <strong>$password</strong></p>";
}


// Handle impersonation
if (isset($_GET['impersonate'])) {
	impersonate($_GET['impersonate']);
	header("Location: forum.php");
	exit();
}

$db = getDB();
$users = $db->query("SELECT id, username FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Handle role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_role') {
	$userId = $_POST['user_id'];
	$newRole = $_POST['role'];

	$stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
	$stmt->execute([$newRole, $userId]);
	echo "<p>User role updated.</p>";
}
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
		<input type="hidden" name="action" value="create_character">
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

	<h2>Change user roles</h2>
	<ul>
		<?php foreach ($users as $u): ?>
			<li>
				<?= htmlspecialchars($u['username']); ?> -
				<form method="POST" action="" style="display:inline;">
					<input type="hidden" name="action" value="change_role">
					<input type="hidden" name="user_id" value="<?= $u['id']; ?>">
					<select name="role">
						<option value="storyteller">Storyteller</option>
						<option value="admin">Admin</option>
						<option value="spc">SPC</option>
						<option value="user">User</option>
					</select>
					<button type="submit">Change Role</button>
				</form>
			</li>
		<?php endforeach; ?>
</body>

</html>
