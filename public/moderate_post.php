<?php
require_once '../src/auth.php';
require_once '../src/db.php';
requireLogin();
$user = currentUser();

$postId = (int)$_POST['post_id'];
$action = $_POST['action'];
$db = getDB();

if ($action === 'delete' && isAdmin()) {
	$stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
	$stmt->execute([$postId]);
}

if ($action === 'edit' && isStoryteller()) {
	header('Location: edit_post.php?id=' . $postId);
	exit;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
