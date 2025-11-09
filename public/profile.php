<?php
require_once '../src/auth.php';
require_once '../src/db.php';
requireLogin();

$user = currentUser();
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Update profile
	$clan = $_POST['clan'] ?? '';
	$affiliation = $_POST['affiliation'] ?? '';
	$signature = $_POST['signature'] ?? '';

	$stmt = $db->prepare("UPDATE users SET clan = ?, affiliation = ?, signature = ? WHERE id = ?");
	$stmt->execute([$clan, $affiliation, $signature, $user['id']]);

	// Handle avatar upload
	if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
		$uploadDir = '../public/uploads/avatars/';
		$filename = uniqid() . '_' . basename($_FILES['avatar']['name']);
		$uploadFile = $uploadDir . $filename;

		if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
			// Update avatar path in database
			$stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
			$stmt->execute([$uploadFile, $user['id']]);
		}
	}

	// Refresh user data
	$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute([$user['id']]);
	$_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);

	header('Location: profile.php');
	exit;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
</head>

<body>
	<?php include 'header.php'; ?>
	<h1>Your Profile</h1>
	<form method="POST" action="profile.php" enctype="multipart/form-data">
		<label for="clan">Clan:</label><br>
		<input type="text" id="clan" name="clan" value="<?= htmlspecialchars($user['clan'] ?? '') ?>"><br><br>

		<label for="affiliation">Affiliation:</label><br>
		<input type="text" id="affiliation" name="affiliation" value="<?= htmlspecialchars($user['affiliation'] ?? '') ?>"><br><br>

		<label for="signature">Signature:</label><br>
		<textarea id="signature" name="signature" rows="4" cols="50"><?= htmlspecialchars($user['signature'] ?? '') ?></textarea><br><br>

		<label for="avatar">Avatar:</label><br>
		<input type="file" id="avatar" name="avatar" accept="image/*"><br><br>

		<input type="submit" value="Update Profile">
	</form>
</body>

</html>
