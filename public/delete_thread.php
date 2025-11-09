<?php
require_once '../src/auth.php';
require_once '../src/db.php';
requireLogin();
if (!isAdmin()) {
	http_response_code(403);
	echo "Forbidden";
	exit;
}
$threadId = (int)$_POST['thread_id'];
$db = getDB();
$stmt = $db->prepare("DELETE FROM threads WHERE id = ?");
$stmt->execute([$threadId]);
header('Location: ' . $_SERVER['HTTP_REFERER']);
