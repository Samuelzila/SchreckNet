<?php
require_once '../src/auth.php';
require_once '../src/forum.php';
requireLogin();
$user = currentUser();
$postId = replyToThread($_POST['thread_id'], $user['id'], $_POST['body']);

if (!empty($_FILES['attachment']['name'])) {
	addAttachment($postId, $_FILES['attachment']);
}

header('Location: thread.php?id=' . $_POST['thread_id']);
