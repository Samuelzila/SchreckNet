<?php
require_once '../src/auth.php';
require_once '../src/forum.php';
requireLogin();
$user = currentUser();
$postId = replyToThread($_POST['thread_id'], $user['id'], $_POST['body']);

if (!empty($_FILES['attachment']['name'])) {
	//debug info($_FILES['attachment']);
	echo "Uploading file...<br>";
	echo "Original name: " . htmlspecialchars($_FILES['attachment']['name']) . "<br>";
	$error = null;

	$file = $_FILES['attachment'];
	$original = basename($file['name']);
	$ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

	if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
		$folder = "uploads/images/";
		$type = "image";
	} else if (in_array($ext, ['pdf', 'txt', 'zip'])) {
		$folder = "uploads/attachments/";
		$type = "document";
	} else {
		$error = "Unsupported file type.";
		exit;
	}

	$stored = $folder . uniqid() . '.' . $ext;
	move_uploaded_file($file['tmp_name'], '../public/' . $stored);

	$db = getDB();
	$stmt = $db->prepare("INSERT INTO attachments (post_id, stored_as, original_name, type) VALUES (?,?,?,?)");
	$stmt->execute([$postId, $stored, $original, $type]);
}

header('Location: thread.php?id=' . $_POST['thread_id']);
