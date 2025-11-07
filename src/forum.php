<?php
require_once "db.php";


// Boards
function getBoards()
{
	$db = getDB();
	return $db->query("SELECT * FROM boards ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
}

function createBoard($name, $description = null)
{
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO boards (name, description) VALUES (?, ?)");
	$stmt->execute([$name, $description]);
}

// Threads in a board
function getThreads($boardId)
{
	$db = getDB();
	$stmt = $db->prepare("SELECT t.*, u.username FROM threads t JOIN users u ON u.id = t.author_id WHERE t.board_id = ? ORDER BY t.created_at DESC");
	$stmt->execute([$boardId]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Posts in a thread
function getPosts($threadId)
{
	$db = getDB();
	$stmt = $db->prepare("SELECT p.*, u.username, u.avatar FROM posts p JOIN users u ON u.id = p.author_id WHERE p.thread_id = ? ORDER BY p.created_at ASC");
	$stmt->execute([$threadId]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createThread($boardId, $authorId, $title, $body)
{
	$db = getDB();
	$db->beginTransaction();
	$stmt = $db->prepare("INSERT INTO threads (board_id, author_id, title, created_at) VALUES (?, ?, ?, datetime('now'))");
	$stmt->execute([$boardId, $authorId, $title]);
	$threadId = $db->lastInsertId();
	$stmt = $db->prepare("INSERT INTO posts (thread_id, author_id, body, created_at) VALUES (?, ?, ?, datetime('now'))");
	$stmt->execute([$threadId, $authorId, $body]);
	$db->commit();
}

function replyToThread($threadId, $authorId, $body)
{
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO posts (thread_id, author_id, body, created_at) VALUES (?, ?, ?, datetime('now'))");
	$stmt->execute([$threadId, $authorId, $body]);
}
