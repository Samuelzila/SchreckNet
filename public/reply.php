<?php
require_once '../src/auth.php';
require_once '../src/forum.php';
requireLogin();
$user = currentUser();
replyToThread($_POST['thread_id'], $user['id'], $_POST['body']);
header('Location: thread.php?id=' . $_POST['thread_id']);
